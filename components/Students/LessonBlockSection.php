<?php

namespace VictorOpusculo\Eadpi\Components\Students;

use VictorOpusculo\Eadpi\Lib\Model\Courses\LessonBlock;
use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\rawText;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class LessonBlockSection extends Component
{
    protected ?LessonBlock $block;

    protected function markup(): Component|array|null
    {
        if (isset($this->block))
            return tag('div', class: 'mb-4', children:
            [
                tag('h3', class: 'text-center', children: text($this->block->title->unwrapOr("Bloco sem nome"))),

                ($this->block->presentation_html->unwrapOr(null) ? 
                    rawText($this->block->presentation_html->unwrapOr(''))
                :
                    null),
                
                ($this->block->video_host->unwrapOr('') === 'youtube' && $this->block->video_url->unwrapOr(null) ?
                    tag('div', class: 'flex mx-auto max-w-[600px]', children:
                        tag('iframe', class: 'flex-1', width: 560, height: 315, src: "https://www.youtube.com/embed/{$this->block->video_url->unwrapOr('')}",
                            title: $this->block->title->unwrapOr(''),
                            frameborder: 0,
                            allowfullscreen: true
                        )
                    )
                :
                    null)
            ]); 
        else
            return null;
    }
}