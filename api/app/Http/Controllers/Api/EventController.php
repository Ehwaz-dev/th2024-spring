<?php

namespace App\Http\Controllers\Api;

use App\DTO\CommentDTO;
use App\DTO\EventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\CreateRequest;
use App\Http\Requests\Api\Event\UpdateRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use App\Utils\Sqid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q', '');

        $words = mb_split(" ", $q);

        $elasticQ = [
            'bool' => [
                'should' => []
            ]
        ];

        if ($q != '') {
            $elasticQ['bool']['should'][] = [
                'match' => [
                    'name' => [
                        'query' => $q
                    ]
                ]
            ];

            foreach ($words as $word) {
                $elasticQ['bool']['should'][] = [
                    'match' => [
                        'tags' => [
                            'query' => $word
                        ]
                    ]
                ];

                $elasticQ['bool']['should'][] = [
                    'match' => [
                        'place' => [
                            'query' => $word
                        ]
                    ]
                ];
            }
        }

        if ($request->query('date')) {
            $dateArr = mb_split(",", $request->query('date'));

            if (count($dateArr) == 2) {
                $dateQ = [
                    [
                        'range' => [
                            'start' => [
                                'gte' => $dateArr[0],
                            ],
                        ]
                    ],
                    [
                        'range' => [
                            'end' => [
                                'lte' => $dateArr[1]
                            ],
                        ]
                    ],
                ];

                $elasticQ['bool']['must'] = $dateQ;
            }
        }

        $eventsElastic = Event::searchQuery($elasticQ)
            ->load(['owner', 'tags'])
            ->execute()
            ->models()
            ->loadCount(['likes', 'comments']);

        return EventResource::collection($eventsElastic);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $dto = new EventDTO(
            name: $request->get('name'),
            description: $request->get('description'),
            start: $request->date('start'),
            end: $request->date('end'),
            requestToJoin: $request->get('requestToJoin'),
            status: Event::STATUS_CREATED,
            ownerId: Auth::id(),
            maxUsers: $request->get('maxUsers'),
            places: $request->get('places'),
            tagIds: $request->get('tags'),
        );

        $event = $this->eventService->create($dto);
        $event->load(['tags']);

        return EventResource::make($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::query()
            ->with(['owner', 'tags'])
            ->withCount(['likes', 'comments'])
            ->findOrFail(Sqid::decode($id));

        return EventResource::make($event);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Event $event)
    {

        if ($event->owner_id != Auth::id()) {
            abort(403);
        }

        $dto = new EventDTO(
            name: $request->get('name'),
            description: $request->get('description'),
            start: $request->date('start'),
            end: $request->date('end'),
            requestToJoin: $request->get('requestToJoin'),
            status: $request->get('status'),
            ownerId: null,
            maxUsers: null,
            places: $request->get('places'),
            tagIds: $request->get('tags'),
        );

        $this->eventService->update($event, $dto);

        return EventResource::make($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::query()->findOrFail($id);

        if ($event->owner_id != Auth::id()) {
            abort(403);
        }

        return response()->json([
            'ok' => $this->eventService->delete($event)
        ]);
    }

    public function join(Event $event)
    {
        if ($event->owner_id == Auth::id()) {
            abort(403);
        }

        return response()->json([
            'ok' => $this->eventService->join($event, Auth::id())
        ]);
    }

    public function accept(Request $request, Event $event)
    {
        $request->validate([
            'user' => 'required|exists:users,id'
        ]);
        if ($event->owner_id != Auth::id()) {
            abort(403);
        }

        return response()->json([
            'ok' => $this->eventService->accept($event, $request->get('user'))
        ]);
    }

    public function comment(Request $request, Event $event)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        return response()->json([
            'ok' => $this->eventService->comment($event, new CommentDTO(
                userId: Auth::id(),
                comment: $request->get('comment'),
            ))
        ]);
    }

    public function like(Request $request, Event $event)
    {
        $this->eventService->like($event,  Auth::id());
        return response()->json([
            'ok' => true
        ]);
    }
}
