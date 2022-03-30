<?php


namespace uukule\BootstrapUi\Table;

use Illuminate\Support\Str;
use uukule\BootstrapUi\Traits\Attr;
use const Couchbase\ENCODER_FORMAT_IGBINARY;

/**
 * Class Th
 * @property string $content
 * @package uukule\BootstrapUi\Table
 */
class Th
{
    use Attr;

    protected string $content;

    public function __construct(string $content = null)
    {
        if (!is_null($content)) {
            $this->content = $content;
        }
    }

    public function __set($name, $value)
    {
        $this->content = $value;
    }

    public function withCheckbox(string $value = '', bool $checked = false, string $name = 'checkbox-ids-console')
    {
        $this->addClass('with-checkbox');

        $name = $name ?? '';
        $checked = $checked ? 'checked' : '';
        $id = Str::uuid();
        $this->content = "<div class=\"checkbox checkbox-css\">
<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" id=\"{$id}\" {$checked} />
<label for=\"{$id}\">&nbsp;</label>
</div>";
        return $this;
    }

    public function html(): string
    {
        $dom = sprintf('<th %s> %s </th>', $this->getAttrString(), $this->content);
        return $dom;
    }
}
