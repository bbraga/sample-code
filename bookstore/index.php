<?php
/**
* March 30th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
*/    
require dirname(__FILE__) . "/lib/config.php"; 
require dirname(__FILE__) . "/lib/book.class.php"; 
 
// instantiate book model
$book = new Book();

// means we are saving
if(!empty($_POST)){
    $book->book($_POST['title'], 
                $_POST['isbn10'], 
                $_POST['isbn13'], 
                $_POST['authorName'], 
                $_POST['publicationType'], 
                $_POST['listPrice']
    );
    $book->id = $_POST['id'];
    if($book->save()){
        $msg = 'Book saved successfully!';
    } else {
        $msg = 'There was a problem saving your book!';
    }
}

// include header
require dirname(__FILE__) . "/elements/header.php";

// include navigation
require dirname(__FILE__) . "/elements/navigation.php";

/* search and books */
require dirname(__FILE__) . "/elements/search.php";

if(!empty($_GET)){
    $edit = false;
    $add = false;
    switch ($_GET['a']) {
        case 'add':
            $add = true;
            require dirname(__FILE__) . "/elements/book.php";
        break;
        case 'edit':
            $edit = true;
            if(!empty($_GET['id']) && empty($_POST)){
                $book = $book->getById($_GET['id']);
            }
            require dirname(__FILE__) . "/elements/book.php";            
        break;
        case 'scrapeBooks':
            require dirname(__FILE__) . "/lib/bookRetriever.class.php";
            $bookRetriever = new BookRetriever();
            $newBooks = $bookRetriever->getBookData();
            if(!empty($newBooks)) {
                $book->truncateTable();
                echo "<span>Table Truncated</span>";
                $book->saveBooks($newBooks);
                echo "<span>Books Imported!</span>";
            }
        break;
        case 'truncateTable':
            $book->truncateTable();
            echo "<span>Table Truncated</span>";
        break;
    }
}

/* search logic */
if(isset($_GET['s'])){
    $books = $book->searchBooks($_GET['s']);    
    foreach($books as $book){
        require dirname(__FILE__) . "/elements/book.php";        
    }    
}    
/* search end */

// include footer
require dirname(__FILE__) . "/elements/footer.php";