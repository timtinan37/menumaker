<?php

namespace PhpCollective\MenuMaker\Adapters;

use Illuminate\Support\HtmlString;

class LaravelCollectiveFormAdapter
{
    /**
     * Open up a new HTML form.
     */
    public static function open(array $options = []): HtmlString
    {
        $method = $options['method'] ?? 'POST';
        $url = self::resolveAction($options);

        $form = html()->form($method, $url);
        $form = self::applyAttributes($form, $options);

        return new HtmlString($form->open());
    }

    /**
     * Open a new model-backed HTML form.
     */
    public static function model($model, array $options = []): HtmlString
    {
        $method = $options['method'] ?? 'POST';
        $url = self::resolveAction($options);

        // Spatie tracks model state globally when modelForm is initialized
        $form = html()->modelForm($model, $method, $url);
        $form = self::applyAttributes($form, $options);

        return new HtmlString($form->open());
    }

    /**
     * Close the current form.
     */
    public static function close(): HtmlString
    {
        html()->endModel();

        return new HtmlString((string) html()->element('form')->close());
    }

    /**
     * Create a text input field.
     */
    public static function text(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->text($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a hidden input field.
     */
    public static function hidden(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->hidden($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a textarea input field.
     */
    public static function textarea(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $textarea = html()->textarea($name, $value);
        return new HtmlString(self::applyAttributes($textarea, $options));
    }

    /**
     * Create a checkbox input field.
     *
     * A hidden input with value 0 is prepended so that unchecked state is
     * still submitted (standard HTML omits unchecked checkboxes entirely).
     */
    public static function checkbox(string $name, $value = 1, ?bool $checked = null, array $options = []): HtmlString
    {
        $hidden   = html()->hidden($name, 0)->forgetAttribute('id')->toHtml();
        $checkbox = html()->checkbox($name, $checked, $value);
        return new HtmlString($hidden . self::applyAttributes($checkbox, $options));
    }

    /**
     * Create a select box field.
     */
    public static function select(string $name, array $list = [], $selected = null, array $options = []): HtmlString
    {
        $select = html()->select($name, $list, $selected);
        return new HtmlString(self::applyAttributes($select, $options));
    }

    /**
     * Create a submit button.
     */
    public static function submit(?string $value = null, array $options = []): HtmlString
    {
        $submit = html()->submit($value);
        return new HtmlString(self::applyAttributes($submit, $options));
    }

    /**
     * Output the CSRF hidden input token field.
     */
    public static function token(): HtmlString
    {
        return new HtmlString(html()->token());
    }

    /**
     * Create a label element.
     */
    public static function label(string $name, ?string $value = null, array $options = [], bool $escape = true): HtmlString
    {
        $label = $escape
            ? html()->label(null, $name)->text($value)
            : html()->label(null, $name)->html($value);
        return new HtmlString(self::applyAttributes($label, $options));
    }

    /**
     * Create a password input field.
     */
    public static function password(string $name, array $options = []): HtmlString
    {
        $input = html()->password($name);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create an email input field.
     */
    public static function email(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->email($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a tel input field.
     */
    public static function tel(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input('tel', $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a number input field.
     */
    public static function number(string $name, $value = null, array $options = []): HtmlString
    {
        $min  = $options['min']  ?? null;
        $max  = $options['max']  ?? null;
        $step = $options['step'] ?? null;
        unset($options['min'], $options['max'], $options['step']);
        $input = html()->number($name, $value, $min, $max, $step);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a date input field.
     */
    public static function date(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->date($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a datetime-local input field (legacy 'datetime' alias).
     */
    public static function datetime(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->datetime($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a datetime-local input field.
     */
    public static function datetimeLocal(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->datetime($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a time input field.
     */
    public static function time(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->time($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a url input field.
     */
    public static function url(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input('url', $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a search input field.
     */
    public static function search(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->search($name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a month input field.
     */
    public static function month(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input('month', $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a week input field.
     */
    public static function week(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input('week', $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a color input field.
     */
    public static function color(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input('color', $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a range input field.
     */
    public static function range(string $name, $value = null, array $options = []): HtmlString
    {
        $min  = $options['min']  ?? null;
        $max  = $options['max']  ?? null;
        $step = $options['step'] ?? null;
        unset($options['min'], $options['max'], $options['step']);
        $input = html()->range($name, $value, $min, $max, $step);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a generic input field.
     */
    public static function input(string $type, string $name, ?string $value = null, array $options = []): HtmlString
    {
        $input = html()->input($type, $name, $value);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a file input field.
     */
    public static function file(string $name, array $options = []): HtmlString
    {
        $input = html()->file($name);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Create a select box over a numeric range.
     */
    public static function selectRange(string $name, int $begin, int $end, $selected = null, array $options = []): HtmlString
    {
        $range = array_combine(range($begin, $end), range($begin, $end));
        return self::select($name, $range, $selected, $options);
    }

    /**
     * Create a select box populated with a range of years.
     */
    public static function selectYear(string $name, int $begin, int $end, $selected = null, array $options = []): HtmlString
    {
        return self::selectRange($name, $begin, $end, $selected, $options);
    }

    /**
     * Create a select box populated with month names.
     */
    public static function selectMonth(string $name, $selected = null, array $options = [], string $format = 'F'): HtmlString
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = \DateTime::createFromFormat('n', (string)$i);
            $months[$i] = $date->format($format);
        }
        return self::select($name, $months, $selected, $options);
    }

    /**
     * Create a radio input field.
     */
    public static function radio(string $name, $value = null, ?bool $checked = null, array $options = []): HtmlString
    {
        $radio = html()->radio($name, $checked, $value);
        return new HtmlString(self::applyAttributes($radio, $options));
    }

    /**
     * Create a button element.
     */
    public static function button(?string $value = null, array $options = []): HtmlString
    {
        $type   = $options['type'] ?? 'button';
        unset($options['type']);
        $button = html()->button($value, $type);
        return new HtmlString(self::applyAttributes($button, $options));
    }

    /**
     * Create a reset button.
     */
    public static function reset(?string $value = null, array $options = []): HtmlString
    {
        $reset = html()->reset($value);
        return new HtmlString(self::applyAttributes($reset, $options));
    }

    /**
     * Create an image input (submit button with image).
     */
    public static function image(string $src, ?string $name = null, array $options = []): HtmlString
    {
        $input = html()->input('image', $name)->attribute('src', $src);
        return new HtmlString(self::applyAttributes($input, $options));
    }

    /**
     * Helper to resolve routes or URLs from options array.
     */
    protected static function resolveAction(array &$options): string
    {
        if (isset($options['route'])) {
            $routeData = $options['route'];
            unset($options['route']);

            return is_array($routeData)
                ? route($routeData[0], array_slice($routeData, 1))
                : route($routeData);
        }

        if (isset($options['url'])) {
            $url = $options['url'];
            unset($options['url']);
            return $url;
        }

        return '';
    }

    /**
     * Helper to loop through the old array options and chain them for Spatie.
     */
    protected static function applyAttributes($spatieElement, array $options)
    {
        // Handle file uploads flag mapping
        if (isset($options['files']) && $options['files'] === true) {
            $spatieElement = $spatieElement->acceptsFiles();
            unset($options['files']);
        }

        // Clean up framework action verbs before mapping HTML attributes
        unset($options['method']);

        foreach ($options as $key => $value) {
            if (method_exists($spatieElement, $key)) {
                $spatieElement = $spatieElement->$key($value);
            } else {
                $spatieElement = $spatieElement->attribute($key, $value);
            }
        }

        return $spatieElement;
    }
}
