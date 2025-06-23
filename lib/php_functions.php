<?php


function js_asset($links = [])
{
    if ($links) {
        foreach ($links as $link) {
            echo "<script src='assets/{$link}.js'>
        </script>";
        }
    }
}
