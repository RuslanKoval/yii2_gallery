(function ($) {

    $(document).on('click', '.edit-image', function (e) {
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href'),
            id = $this.data('id'),
            $modal = $('#edit-image');

        if(!id)
            return false;

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.success) {

                    $modal.find('#fileName').val(response.name);
                    $modal.find('#description').val(response.description);
                    $modal.modal('show');
                }

            },
            error: function(response) {

            }
        });

    });


    $(document).on('click', '.delete-gallery', function (e) {
        e.preventDefault();

        var $this = $(this),
            $block = $this.closest('.gallery__container'),
            url = $this.attr('href');

        if(!url)
            return false;

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert("success");
                    $block.remove();
                }

            },
            error: function(response) {

            }
        });

    });


    $(document).on('click', '.delete-attached', function (e) {
        e.preventDefault();

        var $this = $(this),
            $block = $this.closest('tr'),
            url = $this.attr('href');

        if(!url)
            return false;

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert("success");
                    $block.fadeOut( "slow", function() {
                        $block.remove();
                    });

                }

            },
            error: function(response) {

            }
        });

    });


})(window.jQuery);

