Setup
Clone the repository to your local machine
Copy the .env.example file to a new file called .env and update the values to match your local environment
Run composer install to install the necessary dependencies
Run php artisan migrate to set up the database schema
Start the server by running php artisan serve

Usage
Creating a new real estate entry
To create a new real estate entry, make a POST request to /api/create with the following parameters:

real_estate_entries_type (required): The type of real estate entry (apartment or house)
real_estate_entries_address (required): The address of the real estate entry
real_estate_entries_size (required): The size of the real estate entry in square feet
real_estate_entries_number_of_bedrooms (required): The number of bedrooms in the real estate entry
real_estate_entries_price (required): The price of the real estate entry
real_estate_entries_latitude (optional): The latitude of the real estate entry
real_estate_entries_longitude (optional): The longitude of the real estate entry
Searching for real estate entries by address

To search for real estate entries by type/address/size/number of bedrooms/price, make a GET request to /api/search with the following parameter:

type (optional): The type to search for
address (optional): The address to search for
size (optional): The size to search for
number of bedrooms (optional): The number of bedrooms to search for
price (optional): The price to search for

Searching for real estate entries within a certain radius
To search for real estate entries within a certain radius of a set of coordinates, make a GET request to /api/search-radius with the following parameters:

latitude (required): The latitude of the coordinates to search around
longitude (required): The longitude of the coordinates to search around
radius (required): The radius in kilometers to search within
Improvements
Add unit tests to ensure the functionality of the API
Improve error handling and error messages for API responses
Add authentication and authorization for creating and modifying real estate entries
Add support for sorting and filtering real estate entries in the search endpoints.
