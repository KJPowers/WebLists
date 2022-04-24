# WebLists
A simple list management program designed to run on a standard LAMP stack.

# What it will be

## Must-have
* **Open Source**: I want people to have access to its source code so they can improve it.
* **Multiple Lists per User**: I want my grocery list to be separate from my Christmas shopping list.
* **Multiple Users per List**: I want to share my list with others who may or may not be in close proximity.  This leads to...
* **Responsive Website**: I'm lazy so I only want to write one app; not one for Android, another for iPhone, and a 3rd for PC.  This leads to...
* **PHP**: It should install on a standard LAMP stack.  I know Wicket better than I know PHP but it's harder to get running for other people and a Docker image seems to be overkill.
* **Live Updates**: One user's changes should reflect to another user ASAP.  This probably means AJAX and/or Websockets.
* **Access Control**: Private lists (security through obscurity? Passwords? 2FA?). Group accounts with permissions?
* **UTF-8 Support**: It would be a PITA to migrate it later.

## Nice-to-haves & Future plans
* **Themes**: Users should be able to pick their theme (standard, dark mode, OMG ponies!, etc).
* **UPC Barcode Scanner**: Users should be able to scan barcodes to add items to the list.  Good solutions already exist in JavaScript, but eventually it would be fun to write this in Rust and compile it to WebASM.
* **Heuristic Sort**: Sort your list based on the order you remove the same items from it.  Go through the grocery store in the same order every time?  Sort the items based on this order so the next item to get is always at the top.
* **Pictures**: Thumbnail & high-res images of each product.
* **Substitutions**: What would be an acceptable substitution if the store is out?
* **Recipe Integration**: How cool would it be to add a whole recipe to your list and it automatically adds each ingredient!
* **Inventory Integration**: Track what you already have (amount, location, expiration date, desired amount, etc).  When you add a recipe it checks your inventory and only adds the ingredients you need.
* **QR Scanner**: Inventory lists (each box is its own list and has its own unique QR code).  Datamatrix?
* **Sub-lists**: To-Do lists with multiple steps.  Checklists?
* **Categories**: Not quite a sub-list, but helpful for sorting.
* **Choice of DB**: Support for different DBMSs (MySQL, PostgreSQL, SQLite, ?NoSQL?).
* **i18n**: If it takes off.
