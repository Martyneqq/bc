# Web Application for Registering Finances of Small Businesses
This bachelor project focuses on creating an application designed to help small businesses in the Czech Republic manage their tax records. The existing market for accounting applications in the country is diverse but often lacks features for tax record keeping, especially for small businesses. The goal is to develop a user-friendly, accessible, and free application that is compatible with various operating systems and devices. The application is published on the Internet and incorporates responsiveness for mobile devices. The use of open-source libraries, including Bootstrap for UI elements and Plotly for graph generation, ensures a visually appealing and organized interface.

# Structure

The application is organized into the following main components:

## /class

The `/class` directory contains PHP classes that encapsulate different functionalities of the application. Each class is responsible for a specific aspect of the system, facilitating modularity and maintainability. Classes are then used as an object in most of the files located in the `/bc` file

- `/class`
  - `Alert.php`: generates Bootstrap alert messages.
  - `AppLogic.php`: includes essential methods for generation of the depreciation logic (can be found under the `Odpisy` button available at [`Dlouhodobý majetek`](https://danovaevidencecepela.cz/majetek_dlouhodoby.php)). The window is generated in `select_modal_info.php`.
  - `Authenticator.php`: manages user login, signup, and logout. 
  - `DatabaseHelper.php`: includes most of the SQL-related code. The methods are used in multiple classes.
  - `Head.php`: contains necessary links and scripts. Gives an option to name the page directly from the object.
  - `Header.php`: includes menu-related methods such as information about finances, a manual, or directions.
  - `Records.php`: is a parent class of `Records` pages (`RecordsAssets.php`, `RecordsDemandDebt.php`, etc.). Includes basic methods for changing colors of the text in the `denik.php` files or generating the document numbers.
  - `RecordsAssets.php`: is a class that includes logic for generating `majetek_dlouhodoby.php` page.
  - `RecordsDemandDebt.php`: is a class that includes logic for generating `evidence_pohledavky_a_dluhy.php` page.
  - `RecordsIncomeExpense.php`: is a class that includes logic for generating `evidence_prijmy_a_vydaje.php` page.
  - `RecordsJournal.php`:  is a child class of `Records` and a parent class of `RecordsJournal` classes.
  - `RecordsJournalBank.php`:  is a class that includes logic for generating `denikB.php` page.
  - `RecordsJournalCash.php`:  is a class that includes logic for generating `denikP.php` page.
  - `RecordsJournalIncomeExpense.php`:  is a class that includes logic for generating `denik.php` page.
  - `RecordsMinorAssets.php`:  is a class that includes logic for generating `majetek_drobny.php` page.
  - `TaxRecordsPage.php`:  is a class that includes logic for generating `index.php` page.

## /js

The `/js` repository consists of JavaScript functionalities of the website.

- `/js`
  - `darkMode.js`: changes the color schemes of the website.
  - `getAssetIDSale.js`: get the id from the asset for the `Prodej` pop-up window
  - `jquery-3.6.1.min.js`: a jQuery library.
  - `restrictAddAsset.js`: disables the save button if the user inputs the purchase price lower than 80,000 CZK and at the same time sets the item as tangible.
  - `restrictAddAssetMinor.js`: disables the save button when the purchase price higher or equal to 80,000 CZK is inserted. Furthermore, the item is set to expense without an option to change.
  - `restrictEditAsset.js`: disables the option for editing assets in the `edit1.php`. `edit2.php` and `edit3.php`.
  - `showDepreciation.js`: sends data about the asset into the depreciation pop-up window which appears when clicking on the `Odpisy`.
  - `sort.js`: a sorting algorithm (bubble sort) covering the possibility of sorting document numbers, numerical values with a comma, `dd-mm--YY` dates, and other strings. The original is available at: [W3Schools](https://www.w3schools.com/howto/howto_js_sort_list.asp).
 
## /bc
The `/bc` directory includes a few important unmentioned files:

- `databaseConnection.php`: is the key script that maintains the communication with the database server. The data is saved in the `$connect` variable. This variable is then used for any database access.
- `edit1.php`, `edit2.php`, and `edit3.php`: are instances of the already existing `Records` classes. These are the pages related to item editing after they are created.

# Technologies

The used technologies are:

- HTML
- CSS (including Bootstrap)
- JavaScript
- PHP
- SQL
- Plotly (for graph generation)
- AJAX
- jQuery

# Deployment

The application is hosted by the wedos.cz provider and can be accessed at [danovaevidencecepela.cz](https://danovaevidencecepela.cz). For a preview of the application, you can use the following credentials: 

- **Username:** Firma s.r.o.
- **Password:** 123456

Feel free to explore the features and functionalities with the provided login details.
