@extends('master')

@section('content')

<div style="width:100%; height:100%" id="canvas"></div>

@stop

@section('scripts')

<script src="/js/raphael.min.js"></script>

<script>

var windowWidth = $(window).width(),
    windowHeight = $(window).height();

var canvas = document.getElementById('canvas'),
    paper = new Raphael(canvas, windowWidth, windowHeight);

var topLeftX, topLeftY;

var mouseDown = false;

var rect;

$(canvas).mousedown(function(e)
{
    var offset = $(this).parent().offset();
    topLeftX = e.pageX - offset.left;
    topLeftY = e.pageY - offset.top;

    mouseDown = true;

    rect = paper.rect(topLeftX, topLeftY, 5, 5);
});

$(canvas).mousemove(function(e)
{
    if (mouseDown)
    {
        var offset = $(this).parent().offset();
        var x = e.pageX - offset.left;
        var y = e.pageY - offset.top;

        var newWidth = x - topLeftX;
        var newHeight = y - topLeftY;

        rect.attr({
            width: Math.abs(newWidth),
            height: Math.abs(newHeight)
        });
    }
});

$(canvas).mouseup(function(e)
{
    var offset = $(this).parent().offset();
    var x = e.pageX - offset.left;
    var y = e.pageY - offset.top;

    var newWidth = x - topLeftX;
    var newHeight = y - topLeftY;

    mouseDown = false;

    rect.attr({
        width: Math.abs(newWidth),
        height: Math.abs(newHeight)
    });
});

</script>

@stop