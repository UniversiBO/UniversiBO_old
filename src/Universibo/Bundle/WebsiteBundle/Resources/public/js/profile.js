// setup an "add a tag" link
var $addTagLink = $('<a href="#" class="add_email_link">Aggiungi email</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    var collectionHolder = $('ul.contacts');
    
    collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    
    // add the "add a tag" anchor and li to the tags ul
    collectionHolder.append($newLinkLi);
    
    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addContactForm(collectionHolder, $newLinkLi);
    });
});

function addContactForm(collectionHolder, $newLinkLi) {
    // Get the data-prototype we explained earlier
    var prototype = collectionHolder.attr('data-prototype');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on the current collection's length.
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $tagFormLi.find('.remove-item').last();

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}