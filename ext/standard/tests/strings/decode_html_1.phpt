--TEST--
decode_html: Basic Decoding Tests
--FILE--
<?php

$test_cases = array(
    array("&AElig;", HTML5_TEXT_NODE, 0),

    array("&lt", HTML5_ATTRIBUTE, 0),
    array("&lt;", HTML5_ATTRIBUTE, 0),
    array("&lt,", HTML5_ATTRIBUTE, 0),
    array("&lt,", HTML5_TEXT_NODE, 0),

    array("&rightleftarrows", HTML5_ATTRIBUTE, 0),
    array("&rightleftarrows;", HTML5_ATTRIBUTE, 0),
    array("&rightleftarrows,", HTML5_ATTRIBUTE, 0),
    array("&rightleftarrows,", HTML5_TEXT_NODE, 0),

    array("&#", HTML5_TEXT_NODE, 0),
    array("&#;", HTML5_TEXT_NODE, 0),
    array("&#x;", HTML5_TEXT_NODE, 0),
    array("&#X;", HTML5_TEXT_NODE, 0),
    array("&#X", HTML5_TEXT_NODE, 0),

    array("&#11141114111;", HTML5_TEXT_NODE, 0),
    array("&#x10FFFF0000;", HTML5_TEXT_NODE, 0),

    array("&#x80;", HTML5_TEXT_NODE, 0),
    array("&#x8d;", HTML5_TEXT_NODE, 0),

    array("&#xD800;", HTML5_TEXT_NODE, 0),
    array("&#xDD70;", HTML5_TEXT_NODE, 0),

    array("&#x1f170;", HTML5_TEXT_NODE, 0),

    array("&amp;=", HTML5_ATTRIBUTE, 0),
    array("&amp=", HTML5_ATTRIBUTE, 0),
    array("&amp=", HTML5_TEXT_NODE, 0),

    // &cent is allowed in unambiguous contexts without the ; but
    // it's also a substring of &centerdot; which requires the ;.
    array("&centerdot", HTML5_ATTRIBUTE, 0),
    array("&centerdot", HTML5_TEXT_NODE, 0),

    array("&amp&&amp&&", HTML5_TEXT_NODE, 5),
    array("&amp&&amp;&&", HTML5_TEXT_NODE, 5),
    array("&amp&&amp&&", HTML5_ATTRIBUTE, 5),
    array("&amp&&amp;&&", HTML5_ATTRIBUTE, 5),
    array("&amp&&amp=&", HTML5_TEXT_NODE, 5),
    array("&amp&&amp=&", HTML5_TEXT_NODE, 5),
    array("&amp&&amp/&", HTML5_TEXT_NODE, 5),
);

foreach ($test_cases as $test_case) {
    list($string, $context, $at) = $test_case;

    $match = decode_html($context, $string, $at, $match_length);
    $c = HTML5_ATTRIBUTE === $context ? 'A' : 'T';
    if (isset($match)) {
        echo "{$c}(@{$at} {$string}) {$match_length}:{$match}\n";
    } else {
        echo "{$c}(@{$at} {$string}) (no match)\n";
    }
}
echo "(done)\n";

--EXPECT--
T(@0 &AElig;) 7:Æ
A(@0 &lt) 3:<
A(@0 &lt;) 4:<
A(@0 &lt,) 3:<
T(@0 &lt,) 3:<
A(@0 &rightleftarrows) (no match)
A(@0 &rightleftarrows;) 17:⇄
A(@0 &rightleftarrows,) (no match)
T(@0 &rightleftarrows,) (no match)
T(@0 &#) (no match)
T(@0 &#;) (no match)
T(@0 &#x;) (no match)
T(@0 &#X;) (no match)
T(@0 &#X) (no match)
T(@0 &#11141114111;) 14:�
T(@0 &#x10FFFF0000;) 14:�
T(@0 &#x80;) 6:€
T(@0 &#x8d;) 6:
T(@0 &#xD800;) 8:�
T(@0 &#xDD70;) 8:�
T(@0 &#x1f170;) 9:🅰
A(@0 &amp;=) 5:&
A(@0 &amp=) (no match)
T(@0 &amp=) 4:&
A(@0 &centerdot) (no match)
T(@0 &centerdot) 5:¢
T(@5 &amp&&amp&&) 4:&
T(@5 &amp&&amp;&&) 5:&
A(@5 &amp&&amp&&) 4:&
A(@5 &amp&&amp;&&) 5:&
T(@5 &amp&&amp=&) 4:&
T(@5 &amp&&amp=&) 4:&
T(@5 &amp&&amp/&) 4:&
(done)