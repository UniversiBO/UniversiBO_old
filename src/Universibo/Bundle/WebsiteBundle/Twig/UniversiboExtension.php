<?php
namespace Universibo\Bundle\WebsiteBundle\Twig;
class UniversiboExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'universibo';
    }

    public function getFilters()
    {
        return array(
                'linkify' => new \Twig_Filter_Method($this, 'linkify',
                        array('pre_escape' => 'html',
                                'is_safe' => array('html'))),
                'bbcode' => new \Twig_Filter_Method($this, 'bbcode',
                        array('pre_escape' => 'html',
                                'is_safe' => array('html'))));
    }

    /**
     * Turn all URLs in clickable links.
     *
     * @param  string $value
     * @param  array  $protocols  http/https, ftp, mail, twitter
     * @param  array  $attributes
     * @param  string $mode       normal or all
     * @return string
     */
    public function linkify($value, $protocols = array('http', 'mail'),
            array $attributes = array())
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr = ' ' . $key . '="' . htmlentities($val) . '"';
        }

        $links = array();

        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i',
                function ($match) use (&$links) {
                    return '<' . array_push($links, $match[1]) . '>';
                }, $value);

        // Extract text links for each protocol
        foreach ((array) $protocols as $protocol) {
            switch ($protocol) {
            case 'http':
            case 'https':
                $value = preg_replace_callback(
                        '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i',
                        function ($match) use ($protocol, &$links, $attr) {
                            if ($match[1])
                                $protocol = $match[1];
                            $link = $match[2] ? : $match[3];

                            return '<'
                                    . array_push($links,
                                            "<a $attr href=\"$protocol://$link\">$link</a>")
                                    . '>';
                        }, $value);
                break;
            case 'mail':
                $value = preg_replace_callback(
                        '~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~',
                        function ($match) use (&$links, $attr) {
                            return '<'
                                    . array_push($links,
                                            "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>")
                                    . '>';
                        }, $value);
                break;
            case 'twitter':
                $value = preg_replace_callback('~(?<!\w)[@#](\w++)~',
                        function ($match) use (&$links, $attr) {
                            return '<'
                                    . array_push($links,
                                            "<a $attr href=\"https://twitter.com/"
                                                    . ($match[0][0] == '@' ? ''
                                                            : 'search/%23')
                                                    . $match[1]
                                                    . "\">{$match[0]}</a>")
                                    . '>';
                        }, $value);
                break;
            default:
                $value = preg_replace_callback(
                        '~' . preg_quote($protocol, '~')
                                . '://([^\s<]+?)(?<![\.,:])~i',
                        function ($match) use ($protocol, &$links, $attr) {
                            return '<'
                                    . array_push($links,
                                            "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>")
                                    . '>';
                        }, $value);
                break;
            }
        }

        // Insert all link
        return preg_replace_callback('/<(\d+)>/',
                function ($match) use (&$links) {
                    return $links[$match[1] - 1];
                }, $value);
    }

    public function bbcode($message)
    {
        $preg = array(
                '/(?<!\\\\)\[color(?::\w+)?=(.*?)\](.*?)\[\/color(?::\w+)?\]/si' => "<span style=\"color:\\1\">\\2</span>",
                //          '/(?<!\\\\)\[size(?::\w+)?=(.*?)\](.*?)\[\/size(?::\w+)?\]/si'     => "<span style=\"font-size:\\1\">\\2</span>",
                //          '/(?<!\\\\)\[font(?::\w+)?=(.*?)\](.*?)\[\/font(?::\w+)?\]/si'     => "<span style=\"font-family:\\1\">\\2</span>",
                //          '/(?<!\\\\)\[align(?::\w+)?=(.*?)\](.*?)\[\/align(?::\w+)?\]/si'   => "<div style=\"text-align:\\1\">\\2</div>",
                '/(?<!\\\\)\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si' => "<strong>\\1</strong>",
                '/(?<!\\\\)\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si' => "<em>\\1</em>",
                '/(?<!\\\\)\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si' => "<span style=\"text-decoration:underline\">\\1</span>",
                //          '/(?<!\\\\)\[center(?::\w+)?\](.*?)\[\/center(?::\w+)?\]/si'       => "<div style=\"text-align:center\">\\1</div>",

                //          // [code] & [php]
                //          '/(?<!\\\\)\[code(?::\w+)?\](.*?)\[\/code(?::\w+)?\]/si'           => "<div class=\"bb-code\">\\1</div>",
                //          '/(?<!\\\\)\[php(?::\w+)?\](.*?)\[\/php(?::\w+)?\]/si'             => "<div class=\"bb-php\">\\1</div>",
                // [email]
                '/(?<!\\\\)\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si' => "<a href=\"mailto:\\1\">\\1</a>",
                '/(?<!\\\\)\[email(?::\w+)?=(.*?)\](.*?)\[\/email(?::\w+)?\]/si' => "<a href=\"mailto:\\1\">\\2</a>",
                // [url]
                '/(?<!\\\\)\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si' => "<a href=\"http://www.\\1\" target=\"_blank\" title=\"Questo link apre una nuova pagina\">\\1</a>",
                //          '/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "<a href=\"\\1\" target=\"_blank\">\\1</a>",
                //          '/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si'      => "<a href=\"\\1\" target=\"_blank\">\\2</a>",
                '/(?<!\\\\)\[url(?::\w+)?=(.*?)?\stype=extern?\](.*?)\[\/url(?::\w+)?\]/si' => "<a href=\"\\1\" target=\"_blank\" title=\"Questo link apre una nuova pagina\">\\2</a>",
                '/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si' => "<a href=\"\\1\">\\1</a>",
                '/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si' => "<a href=\"\\1\">\\2</a>",
                //[acronym]
                '/(?<!\\\\)\[acronym(?::\w+)?=(.*?)?\](.*?)\[\/acronym(?::\w+)?\]/si' => "<acronym title=\"\\1\">\\2</acronym>",
                //[lang]
                '/(?<!\\\\)\[lang(?::\w+)?=(.*?)?\](.*?)\[\/lang(?::\w+)?\]/si' => "<span lang=\"\\1\">\\2</span>",
                //          // [img]
                //          '/(?<!\\\\)\[img(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si'             => "<img src=\"\\1\" alt=\"\\1\" />",
                //          '/(?<!\\\\)\[img(?::\w+)?=(.*?)x(.*?)\](.*?)\[\/img(?::\w+)?\]/si' => "<img width=\"\\1\" height=\"\\2\" src=\"\\3\" alt=\"\\3\" class=\"bb-image\" />",
                //          // [quote]
                //          '/(?<!\\\\)\[quote(?::\w+)?\](.*?)\[\/quote(?::\w+)?\]/si'         => "<div>Quote:<div class=\"bb-quote\">\\1</div></div>",
                //          '/(?<!\\\\)\[quote(?::\w+)?=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](.*?)\[\/quote\]/si'   => "<div>Quote \\1:<div class=\"bb-quote\">\\2</div></div>",
                // [list]
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\*(?::\w+)?\](.*?)(?=(?:\s*<br\s*\/?>\s*)?\[\*|(?:\s*<br\s*\/?>\s*)?\[\/?list)/si' => "\n<li>\\1</li>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list(:(?!u|o)\w+)?\](?:<br\s*\/?>)?/si' => "\n</ul>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:u(:\w+)?\](?:<br\s*\/?>)?/si' => "\n</ul>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:o(:\w+)?\](?:<br\s*\/?>)?/si' => "\n</ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(:(?!u|o)\w+)?\]\s*(?:<br\s*\/?>)?/si' => "\n<ul>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:u(:\w+)?\]\s*(?:<br\s*\/?>)?/si' => "\n<ul>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:o(:\w+)?\]\s*(?:<br\s*\/?>)?/si' => "\n<ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=1\]\s*(?:<br\s*\/?>)?/si' => "\n<ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=i\]\s*(?:<br\s*\/?>)?/s' => "\n<ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=I\]\s*(?:<br\s*\/?>)?/s' => "\n<ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=a\]\s*(?:<br\s*\/?>)?/s' => "\n<ol>",
                '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=A\]\s*(?:<br\s*\/?>)?/s' => "\n<ol>",
                // escaped tags like \[b], \[color], \[url], ...
                '/\\\\(\[\/?\w+(?::\w+)*\])/' => "\\1");
        $message = preg_replace(array_keys($preg), array_values($preg),
                $message);

        return $message;
    }

}
