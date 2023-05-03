jQuery(document).ready(function($){

    if ($('#wheel-exclude-page').length > 0) {
        new SlimSelect({
            select: '#wheel-exclude-page',
        });
    }

        if ($('#wheel-include-page').length > 0) {
            new SlimSelect({
                select: '#wheel-include-page',
            });

    }
});