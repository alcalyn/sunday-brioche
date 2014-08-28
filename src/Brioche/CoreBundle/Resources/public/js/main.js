$(function () {
    enableSizeSelector();
    enableSliders();
    enableSelect2();
    Brioche.init();
});

function enableSizeSelector() {
    $('#size-tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        var size = $(this).attr('href').split('-')[1];
        $('input[name=size]').val(size);
    });
}

function enableSliders() {
    $('#slider-butter').slider({
        formater: formaterButter
    });
    $('#slider-sugar').slider({
        formater: formaterSugar
    });
}

function enableSelect2() {
    $('.select2-with-search').select2();
    $('.select2').select2({
        minimumResultsForSearch: -1
    });
}

function formaterButter(value) {
    return ['Moins beurée', 'Normal', 'Bien beurrée'][value];
}

function formaterSugar(value) {
    return ['Moins sucrée', 'Normal', 'Bien sucrée'][value];
}

var Brioche =
{
    init: function ()
    {
        if ($('section.round').size() > 0) {
            BriocheRound.init();
        }
        
        if ($('section.type').size() > 0) {
            BriocheType.init();
        }
    }
};

var BriocheRound =
{
    init: function ()
    {
        var $form = $('.form-round');
        var $formInput = $('input[name=round]');
        
        $('.tournees .tournee:not(.full) button').click(function () {
            var round = $(this).closest('.tournee').data('roundId');
            $formInput.val(round);
            $form.submit();
        });
    }
};

var BriocheType =
{
    init: function ()
    {
        $('section.type .type-vendeene').click(function () {
            BriocheType.select(0);
            return false;
        });

        $('section.type .type-parisienne').click(function () {
            BriocheType.select(1);
            return false;
        });
    },
    
    select: function (type)
    {
        $('input[name=type]').val(type);
        $('form.form-type').submit();
    }
};
