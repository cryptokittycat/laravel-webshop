    function post(url) {
        $('#searchcontent').html('<i class="loading fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }});
        $.ajax({
            type: "POST",
            url: url,
            success: function (data) {
                $('#searchcontent').html(data);
            },
            error: function (data) {
                console.log('error getting items.');
            }
        });
    }

    /* get category from url */
    function getCategory() {
        let href = window.location.href;
        let index = href.indexOf("#");
        let cat = href.substr(index + 1);
        if(index == -1) {
            cat = '';
        }
        return cat;
    }

$(document).ready(function() {

    /* search input */
	$('#searchbox').on('input', function() {
        let order = $('#order-by option:selected').val();
        let cat = getCategory();
        let url = 'http://localhost:8000/'+(cat.length == 0 ? "search/"+order+'/'+this.value : "cat/"+cat+"/search/"+order+'/'+ this.value);
		post(url);
	});

    /* order by select */
    $('#order-by').on('change', function() {
        let order = $('#order-by option:selected').val();
        let cat = getCategory();
        let search = $('#searchbox').val();
        let url = 'http://localhost:8000/'+(cat.length == 0 ? "search/"+order+'/'+search : "cat/"+cat+"/search/"+order+'/' + search);
        post(url);
    });

    /* sidebar category link */
    $('.category_link').click('input', function() {
        $(this).parent().addClass('selected').siblings().removeClass('selected');
        let order = $('#order-by option:selected').val();
        let search = $('#searchbox').val();
        let cat = $(this).attr('data-cat');
        let url = 'http://localhost:8000/'+(cat.length == 0 ? "search/"+order+'/'+search : "cat/"+cat+"/search/"+order+'/' + search);
        post(url);
    });

    /* details page color picker */
    $('.colorpick').click(function() {
        if($(this).hasClass('colorpick-active')) {
            $(this).removeClass('colorpick-active');
        }else {
            $(this).addClass('colorpick-active');
            $(this).siblings().removeClass('colorpick-active');
        }
    });

    /* if theres a #category in the url, load the results */
    let cat = getCategory();
    if(cat.length != 0) {
        $('#cat_'+cat).parent().addClass('selected').siblings().removeClass('selected');
        let order = $('#order-by option:selected').val();
        let url = 'http://localhost:8000/cat/'+order+'/'+cat;
        post(url);
    }

    $('#product-table').tablesorter();

});