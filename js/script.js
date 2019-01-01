$(document).ready(function () {
    $("form").on("submit", function(e) {
        e.preventDefault();
        $.getJSON("search.php?q=" + $("#query").val(), function (data) {
            $("#result").html("<div class='row bg-light'><div class='col-md-12'> Результат поиска по запросу: " + data.q + "</div></div>");
            $.each(data.result, function (key, item) {
                $("#result").append('<div class="card">' +
                    '    <div class="card-header">' +
                    '        <a data-toggle="collapse" href="#' + item.id + '" aria-expanded="true" aria-controls="test-block">' +
                                item.title + " (Автор: " + item.author + ", опубликовано " + item.published + ")" +
                    '        </a>' +
                    '    </div>' +
                    '    <div id="' + item.id + '" class="collapse">' +
                    '        <div class="card-block">' +
                    '           <iframe class="video w100" width="640" height="360" src="//www.youtube.com/embed/' + item.id + '" frameborder="0" allowfullscreen></iframe>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>');
            });
        });
    });
});