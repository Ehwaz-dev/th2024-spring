<?php
namespace App\Services;

use App\DTO\CommentDTO;
use App\DTO\EventDTO;
use App\Models\City;
use App\Models\Event;
use App\Models\Comment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventService
{

    public function attachCities(Event $event, array $places)
    {
        $citiesId = [];
        $citiesName = [];
        foreach ($places as $place) {
            $type = $place['type'];
            $name = $place['name'];

            switch ($type) {
                case 'region':
                    $cities = City::query()
                        ->select('id')
                        ->where('region', $name)
                        ->get()
                        ->map(fn($c) => $c->id)->toArray();

                    $citiesId = array_merge($citiesId, $cities);
                    break;
                case 'city':
                    $citiesName[] = $name;
            }
        }

        if (count($citiesName) != 0) {
            $cities = City::query()
                ->select('id')
                ->whereIn('city', $citiesName)
                ->get()
                ->map(fn($c) => $c->id)->toArray();
            $citiesId = array_merge($citiesId, $cities);
        }

        $event->cities()->attach($citiesId);
    }
    public function create(EventDTO $dto): Event
    {
        $event = null;
        DB::transaction(function () use ($dto, &$event) {
            $event = Event::create([
                'owner_id' => $dto->ownerId,
                'name' => $dto->name,
                'description' => $dto->description,
                'max_users' => $dto->maxUsers,
                'start' => $dto->start,
                'end' => $dto->end,
                'places' => $dto->places,
            ]);

            /**@var $event Event*/
            $event->tags()->attach($dto->tagIds);

            $this->attachCities($event, $dto->places);

            $event->searchable();
        });


        return $event;
    }

    public function update(Event $event, EventDTO $dto): void
    {
       DB::transaction(function () use ($dto, $event) {
           $event->tags()->detach(DB::table('event_tag')
               ->select('tag_id')
               ->where('event_id', $event->id)
               ->get()
               ->map(fn($q) => $q->tag_id)->toArray());
           $event->tags()->attach($dto->tagIds);


           $event->cities()->detach(DB::table('rc_event')
               ->select('rc_id')
               ->where('event_id', $event->id)
               ->get()
               ->map(fn($q) => $q->rc_id)->toArray());

           $this->attachCities($event, $dto->places);

           $event->update([
               'name' => $dto->name,
               'description' => $dto->description,
               'start' => $dto->start,
               'end' => $dto->end,
               'places' => $dto->places,
           ]);
       });
    }

    public function delete(Event $event): ?bool
    {
        return $event->delete();
    }

    public function join(Event $event, int $userId): bool
    {
        if (isset($event->max_user)) {
            $users_count = $event->users()->count();

            if ($users_count >= $event->max_user) {
                return false;
            }
        }

        $event->users()->attach($userId, ['accepted' => !$event->request_to_join]);
        return true;
    }

    public function accept(Event $event, int $userId): bool
    {
        $event->requestedUsers()->updateExistingPivot($userId, ['accepted' => true]);
        return true;
    }

    public function comment(Event $event, CommentDTO $dto): Comment
    {
        return $event->comments()->create([
            'user_id' => $dto->userId,
            'comment' => $dto->comment,
        ]);
    }

    public function like(Event $event, int $userId)
    {
        $l = $event->likes()->where(['user_id' => $userId])->first();
        if (isset($l)) {
            $l->delete();
            return;
        }

        $event->likes()->create(['user_id' => $userId]);
    }

    public function doneExpired()
    {
        Event::query()->where('end', '<=', Carbon::now())->update(['status' => Event::STATUS_DONE]);
        // TODO: check achievements
    }
}
