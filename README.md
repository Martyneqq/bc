# Web Application for Registering Finances of Small Businesses
This bachelor project focuses on creating an application designed to help small businesses in the Czech Republic manage their tax records. The existing market for accounting applications in the country is diverse but often lacks features for tax record keeping, especially for small businesses. The goal is to develop a user-friendly, accessible, and free application that is compatible with various operating systems and devices. The application is published on the Internet and incorporates responsiveness for mobile devices. The use of open-source libraries, including Bootstrap for UI elements and Plotly for graph generation, ensures a visually appealing and organized interface.

# Structure

The application is organized into the following main components:

## /class

The `/class` directory contains PHP classes that encapsulate different functionalities of the application. Each class is responsible for a specific aspect of the system, facilitating modularity and maintainability. Classes are then used as an object in most of the files located in the `/bc` file

- `/class`
  - `Alert.php`: generates Bootstrap alert messages.
  - `AppLogic.php`: includes essential methods for generation of the depreciation logic (can be found under the `Odpisy` button available at [`Dlouhodobý majetek`](https://danovaevidencecepela.cz/majetek_dlouhodoby.php)).
  - `Authenticator.php`: manages user login, signup, and logout. 
  - `DatabaseHelper.php`: includes most of the SQL-related code. The methods are used in multiple classes.
  - `Head.php`: contains necessary links and scripts. Gives an option to name the page directly from the object.
  - `Header.php`: includes menu-related methods such as information about finances, a manual, or directions.
  - `Records.php`: is a parent class of every page excluding `login.php` and `signup.php`. Includes basic methods for changing colors of the text in the `denik.php` files or generating the document numbers.
  - `RecordsAssets.php`: 
  - `RecordsDemandDebt.php`: 
  - `RecordsIncomeExpense.php`: 
  - `RecordsJournal.php`: 
  - `RecordsJournalBank.php`: 
  - `RecordsJournalCash.php`: 
  - `RecordsJournalIncomeExpense.php`: 
  - `RecordsMinorAssets.php`: 
  - `TaxRecordsPage.php`: 

# Technologies

- HTML
- CSS (including Bootstrap)
- JavaScript
- PHP
- SQL
- Plotly (for graph generation)
- AJAX
- jQuery

# Deployment

The application has been published using wedos.cz provider and is available to visit at [danovaevidencecepela.cz](danovaevidencecepela.cz).
