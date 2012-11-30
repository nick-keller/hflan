<?php

namespace hflan\BlogBundle\Service;

class hflanTextFormat extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            'toHTML' => new \Twig_Filter_Method($this, 'toHTML'),
            'toPlainText' => new \Twig_Filter_Method($this, 'toPlainText'),
            'nl2br' => new \Twig_Filter_Method($this, 'nl2br'),
            'ucFirst' => new \Twig_Filter_Method($this, 'ucFirst'),
        );
    }

    public function toHTML($text)
    {
        // basic
        $string = preg_replace('#`#', "'", $text);
        $string = preg_replace('#<#', "&lt;", $string);
        $string = "<p>\r\n\r\n".$string."\r\n\r\n</p>";
        $string = preg_replace('#( |\t)+#', " ", $string);
        $string = preg_replace('#\n( |\t)+#', "\n", $string);
        $string = preg_replace('#( |\t)+\r#', "\r", $string);
        $string = preg_replace('#(\r\n){2,}#', "\r\n\r\n", $string);

        // titres
        $string = preg_replace('#\n\#{2}( |\t)?([^\r]+)\r#', "</p><h5>$2</h5><p>", $string);
        $string = preg_replace('#\n\#( |\t)?([^\r]+)\r#', "</p><h4>$2</h4><p>", $string);
        $string = preg_replace('#( |\t)?\#+(</h[3-4]>)#', "$2", $string);

        // citations
        $i = 0;
        while(preg_match('#\n>{'.++$i.'}#', $string))
            $string = preg_replace('#((\n>{'.$i.'}[^\r]*\r)+)#', "</p><blockquote><p>$1</p></blockquote><p>", $string);
        $string = preg_replace('#\n>+#', "\n", $string);

        // paragraphs, retour ligne
        $string = preg_replace('#(\r\n){2}#', "</p>\r\n\r\n<p>", $string);
        $string = preg_replace('#\s*(</?[a-z]+>)\s*#', "$1", $string);
        $string = preg_replace('#<p></p>#', "", $string);
        $string = preg_replace('#\r\n#', "<br/>", $string);

        // emphase
        $string = preg_replace('#\*\*((.(?<!</p>))+)\*\*#sU', '<b>$1</b>', $string);
        $string = preg_replace('#(?<!http:)//((.(?<!</p>))+)(?<!http:)//#sU', '<em>$1</em>', $string);
        $string = preg_replace('#__((.(?<!</p>))+)__#sU', '<span style="text-decoration: underline">$1</span>', $string);
        $string = preg_replace('#~~((.(?<!</p>))+)~~#sU', '<span style="text-decoration: line-through">$1</span>', $string);
        $string = preg_replace('#\+\+\+((.(?<!</p>))+)\+\+\+#sU', '<span style="font-size: 20px">$1</span>', $string);
        $string = preg_replace('#\+\+((.(?<!</p>))+)\+\+#sU', '<span style="font-size: 16px">$1</span>', $string);
        $string = preg_replace('#---((.(?<!</p>))+)---#sU', '<span style="font-size: 10px">$1</span>', $string);
        $string = preg_replace('#--((.(?<!</p>))+)--#sU', '<span style="font-size: 12px">$1</span>', $string);

        //alignements
        $string = preg_replace('#<p>%((.(?<!%</p>))+)%</p>#sU', '<p style="text-align:center">$1</p>', $string);

        //youtube
        $string = preg_replace('#&lt; ?http://www\.youtube\.com/watch\?v=([a-zA-Z0-9-]{11})[^>]*>#', '</p><div style="width:450px; margin:auto;"><iframe width="450" height="286" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div><p>', $string);
        $string = preg_replace('#&lt; ?http://www.youtube.com/v/([a-zA-Z0-9-]{11})[^>]*>#', '</p><div style="width:450px; margin:auto; max-width:90%; overflow:hidden;"><iframe width="450" height="286" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div><p>', $string);
        $string = preg_replace('#&lt; ?http://youtu.be/([a-zA-Z0-9-]{11})[^>]*>#', '</p><div style="width:450px; margin:auto; max-width:90%; overflow:hidden;"><iframe width="450" height="286" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div><p>', $string);
        $string = preg_replace('#&lt; ?http://www.youtube.com/embed/([a-zA-Z0-9-]{11})[^>]*>#', '</p><div style="width:450px; margin:auto; max-width:90%; overflow:hidden;"><iframe width="450" height="286" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div><p>', $string);

        //liens sur image
        $string = preg_replace('#&lt; ?(http[^" \t]+)( |\t)?" ?&lt; ?(http.+\.(png|jpg|jpeg|bmp|gif)) ?> ?" ?>#i', '<a href="$1"><img src="$3" alt="[image]" style="max-width:90%" /></a>', $string);

        // images
        $string = preg_replace('#&lt; ?(http[^>]+\.(png|jpg|jpeg|bmp|gif)) ?>#i', '<img src="$1" alt="[image]" style="max-width:90%" />', $string);

        // liens
        $string = preg_replace('#&lt; ?(http[^" \t]+)( |\t)?" ?([^"]+) ?" ?>#', '<a href="$1" class="link">$3</a>', $string);
        $string = preg_replace('#&lt; ?(http[^ >]+) ?>#', '<a href="$1" class="link">$1</a>', $string);

        // plans
        /*$string = preg_replace_callback('#&lt; ?plan ?: ?([^>]+) ?>#', 'del_space', $string);*/

        // netoyage
        //$string = preg_replace('#</blockquote>#', "</div>", $string);
        //$string = preg_replace('#<blockquote>#', "<div class=\"citation\">", $string);
        $string = preg_replace('#\s*(</?(p|div|br)/?>)\s*#', "$1", $string);
        $string = preg_replace('#<p></p>#', "", $string);

        return $string;
    }

    public function toPlainText($text, $length = 0)
    {
        $string = $this->toHTML($text);
        $string = preg_replace('#<.+>#U', '', $string);

        if($length != 0 && strlen($string) > $length && strpos($string, ' ', $length) !== false)
            $string = substr($string, 0, strpos($string, ' ', $length)).'...';

        return $string;
    }

    public function nl2br($text)
    {
        // basic
        $string = preg_replace('#`#', "'", $text);
        $string = preg_replace('#<#', "&lt;", $string);
        $string = "<p>\r\n\r\n".$string."\r\n\r\n</p>";
        $string = preg_replace('#( |\t)+#', " ", $string);
        $string = preg_replace('#\n( |\t)+#', "\n", $string);
        $string = preg_replace('#( |\t)+\r#', "\r", $string);
        $string = preg_replace('#(\r\n){2,}#', "\r\n\r\n", $string);

        // paragraphs, retour ligne
        $string = preg_replace('#(\r\n){2}#', "</p>\r\n\r\n<p>", $string);
        $string = preg_replace('#\s*(</?[a-z]+>)\s*#', "$1", $string);
        $string = preg_replace('#<p></p>#', "", $string);
        $string = preg_replace('#\r\n#', "<br/>", $string);
        $string = preg_replace('#\s*(</?(p|div|br)/?>)\s*#', "$1", $string);

        return $string;
    }

    public function ucFirst($str)
    {
        return ucfirst($str);
    }

    public function getName()
    {
        return 'hflan_textFormat';
    }
}