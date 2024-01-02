# Web Application for Registering Finances of Small Businesses
This bachelor project focuses on creating an application designed to help small businesses in the Czech Republic manage their tax records. The existing market for accounting applications in the country is diverse but often lacks features for tax record keeping, especially for small businesses. The goal is to develop a user-friendly, accessible, and free application that is compatible with various operating systems and devices. The application is published on the Internet and incorporates responsiveness for mobile devices. The use of open-source libraries, including Bootstrap for UI elements and Plotly for graph generation, ensures a visually appealing and organized interface.

# Structure

The application is organized into the following main components:

## /classes

The `/class` directory contains PHP classes that encapsulate different functionalities of the application. Each class is responsible for a specific aspect of the system, facilitating modularity and maintainability.

- `/class`
  - `Alert.php`: generates Bootstrap alert messages.
  - `AppLogic.php`: includes essential methods for generation of the depreciation logic (can be found under the `Odpisy` button available at [https://danovaevidencecepela.cz/majetek_dlouhodoby.php](https://danovaevidencecepela.cz/majetek_dlouhodoby.php))
  - `Authenticator.php`: 
  - `DatabaseConnect.php`: 
  - `DatabaseHelper.php`: 
  - `Head.php`: 
  - `Header.php`: 
  - `Records.php`: 
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
