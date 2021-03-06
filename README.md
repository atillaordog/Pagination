Pagination
=============

This is a very basic pagination class that helps easily create an array with the links and numbers of pagination.

Using this class is as easy as follows:

```php
// First we have to get the total elements

// Create the limit, offset and other useful data first
$pag = Pagination::paginate($total_nr, $current_page, 20);

// Here we get our data using limit, offset

// Build the pagination
$pagination = new Pagination(array(
	'total' => $total_nr, // The nmber of all elements
	'items_per_page' => $pag['pagination'], // How many items will there be on the page
	'base_url' => $url,  // The base url used, this can contain $_GET elements too, they will not be overwritten
	'current_page' => $pag['current'], // Current page we are on
	'links_to_show' => 3, // How many links to show between the 3 dots
	'page_name' => 'sm_page', // Name of the $_GET parameter to use
	'first_string' => '<<', // String that is used for the FIRST link
	'prev_string' => '<', // string used for the PREVIOUS link
	'dots_string' => '...', // String used for the dots between numbers
	'next_string' => '>', // String used for the NEXT link
	'last_string' => '>>' // String used for the LAST link
));

// Generate the array of pagination links
$pagination_links = $pagination->render();
```

You can also set the mentioned variables after initialization like:

```php
$pagination->base_url = '';
```
Note that you have to set all variables before calling render()