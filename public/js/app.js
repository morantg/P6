var $collectionHolder;
var $collectionHolder2;

// setup an "add a media" link
var $addMediaButton = $('<button type="button" class="add_media_link btn btn-primary">Ajouté une image</button>');
var $newLinkLi = $('<li></li>').append($addMediaButton);

var $addVideoButton = $('<button type="button" class="add_video_link btn btn-primary">Ajouté une vidéo</button>');
var $newLinkLiVid = $('<li></li>').append($addVideoButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.media');

    $collectionHolder2 = $('ul.video');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // add a delete link to all of the existing tag form li elements
    $collectionHolder2.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder2.append($newLinkLiVid);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder2.data('index2', $collectionHolder2.find(':input').length);

    $addMediaButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });

    $addVideoButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addVideoForm($collectionHolder2, $newLinkLiVid);
    });
});


function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addVideoForm($collectionHolder2, $newLinkLiVid) {
    // Get the data-prototype explained earlier
    var prototype2 = $collectionHolder2.data('prototype');

    // get the new index
    var index2 = $collectionHolder2.data('index2');

    var newFormVid = prototype2;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newFormVid = newFormVid.replace(/__name__/g, index2);

    // increase the index with one for the next item
    $collectionHolder2.data('index2', index2 + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLiVid = $('<li></li>').append(newFormVid);
    $newLinkLiVid.before($newFormLiVid);

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLiVid);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-danger">Delete this media</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}
