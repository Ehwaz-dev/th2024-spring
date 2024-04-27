<?php
declare(strict_types=1);

use Elastic\Adapter\Indices\Mapping;
use Elastic\Adapter\Indices\Settings;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

final class CreateEventsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {

       Index::create('events',function (Mapping $mapping, Settings $settings) {
           $mapping->text('name');
           $mapping->text('tags');
           $mapping->text('place');
           $mapping->date('start');
           $mapping->date('end');
          $settings->analysis( [
              "filter" => [
                  "ng" => [
                      "type" => "edge_ngram",
                      "min_gram" => 4,
                      "max_gram" => 6
                  ],
                  "ru_RU" => [
                      "type" => "hunspell",
                      "language" => "ru_RU",
                      "dedup" => "true"
                  ],
                  "ru_stop" => [
                      "type" => "stop",
                      "stopwords" => "а,без,более,бы,был,была,были,было,быть,в,вам,вас,весь,во,вот,все,всего,всех,вы,где,да,даже,для,до,его,ее,если,есть,еще,же,за,здесь,и,из,или,им,их,к,как,ко,когда,кто,ли,либо,мне,может,мы,на,надо,наш,не,него,нее,нет,ни,них,но,ну,о,об,однако,он,она,они,оно,от,очень,по,под,при,с,со,так,также,такой,там,те,тем,то,того,тоже,той,только,том,ты,у,уже,хотя,чего,чей,чем,что,чтобы,чье,чья,эта,эти,это,я"
                  ],
              ],
              "analyzer" => [
                  "default" => [
                      "filter" => [
                          "lowercase",
                          "ng",
                          "ru_RU",
                          "ru_stop"
                      ],
                      "char_filter" => [
                          "html_strip"
                      ],
                      "tokenizer" => "standard"
                  ]
              ]
          ]);
       });

    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('events');
    }
}
