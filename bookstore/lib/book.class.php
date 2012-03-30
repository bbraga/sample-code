<?php
/**
* March 30th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
*/
class Book {
    protected $id = null;
    protected $title = '';
    protected $isbn10 = '';
    protected $isbn13 = '';
    protected $authorName = '';
    protected $publicationType = '';
    protected $listPrice = '';
    
    const TABLE_NAME = 'books';
    
    /**
    * Class constructor 
    * 
    * @param string $title
    * @param string $isbn10
    * @param string $isbn13
    * @param string $authorName
    * @param string $publicationType
    * @param string $listPrice
    * @return Book
    */
    public function book($title = '', $isbn10 = '', $isbn13 = '', $authorName = '', $publicationType = '', $listPrice = ''){
        $this->title = mysql_escape_string($title);
        $this->isbn10 = mysql_escape_string($isbn10);
        $this->isbn13 = mysql_escape_string($isbn13);
        $this->authorName = mysql_escape_string($authorName);
        $this->publicationType = mysql_escape_string($publicationType);
        $this->listPrice = mysql_escape_string($listPrice);        
    }
    
    /**
    * Saves the active book to the db, depending on the context this works as and update or as an insert
    * 
    */
    public function save(){
        if(empty($this->id)){
            $query = "INSERT INTO ". book::TABLE_NAME ."(title, isbn10, isbn13, author_name, publication_type, list_price)
                VALUES("
                .$this->getFieldsAndValuesSQL().
                "
                );";
        } else {
            $query = "UPDATE " . book::TABLE_NAME . " SET " . $this->getFieldsAndValuesSQL(true) . " WHERE `id`=" . $this->id . ';';
        }   
        return $result = mysql_query($query);         
    }
    
    /**
    * Retrieves books by a search query on the title or isbn10 or isbn13, right now it works for partial search queries on isbns as well
    * 
    * @param string $searchQuery
    * @return Book
    */
    public function searchBooks($searchQuery){
        $searchQuery = trim($searchQuery);
        if(strlen($searchQuery)){
            $search = "%".mysql_escape_string($searchQuery)."%"; 
            $query = "select * from ". book::TABLE_NAME ." WHERE `title` LIKE '$search' || `isbn10` LIKE '$search' || `isbn13` LIKE '$search';";    
        } else {
            $query = "select * from ". book::TABLE_NAME . ";";    
        }        
        if($result = mysql_query($query)){
            return $this->parseDbResult($result);
        }
        return false;
    }
    
    /**
    * Parses db query results and converts it to a native book assoc object array 
    * 
    * @param mysqlQueryResult $mysqlQuery
    * @return Book
    */
    private function parseDbResult($mysqlQuery){
        $results = array();
        while($row = mysql_fetch_array($mysqlQuery,MYSQL_ASSOC)) {
            $results[] = $this->arrayToBook($row);    
        }       
        return $results;
    }
    
    /**
    * compiles the list of fields and its values for the insert/update query
    * 
    * @param bool $update
    */
    private function getFieldsAndValuesSQL($update = false) {
        $fieldsValues = '';
        $classVars = get_class_vars(get_class($this));
        $i = 1;
        $total = count($classVars);
        foreach ($classVars as $property => $value) {
            if($i == 1){
                $i++;
                continue;
            }
            if($update){
                $fieldsValues .= ' `'. $this->camelCaseToUnderscore($property) . "` = '{$this->$property}'". ($i == $total ? '':',') . "\n";        
            }else{
                $fieldsValues .= "'{$this->$property}'". ($i == $total ? '': ',') . "\n";        
            }
            $i++;
        }   
        return $fieldsValues;     
    }
    
    /**
    * converts camel case strings into its underscore counterpart
    * 
    * @param string $name
    * @return string
    */
    private function camelCaseToUnderscore($name) {
        $name = preg_replace('~([A-Z])~', '_$1', $name);
        $name = strtolower($name);
        return trim($name, ' _');
    }
 
    /**
    * magic getter
    * 
    * @param string $property
    */
    public function __get($property) {
        return $this->$property;
    }
       
    /**
    * magic setter, escapes strings while at it
    *    
    * @param string $property
    * @param string $value
    */
    public function __set($property, $value) {
        $this->$property = mysql_escape_string($value);
    }
    
    /**
    * Retrieves a book by its ID
    * 
    * @param mixed $bookId
    * @return Book
    */
    public function getById($bookId) {
        $result = mysql_fetch_assoc(
            mysql_query("SELECT * FROM ". book::TABLE_NAME ." WHERE `id`='".mysql_escape_string($bookId)."';")
        );
        return $this->arrayToBook($result);
    }
    
    /**
    * Empties the books table so that we can populate it again from the scrape data
    * 
    */
    public function truncateTable() {
        return mysql_query("TRUNCATE TABLE ". book::TABLE_NAME .";");
    }
    
    /**
    * saves a books array list
    * 
    * @param array $books
    */
    public function saveBooks($books) {
        foreach ($books as $book) {
            $this->book();
            $this->title = $book['title'];
            $this->isbn10 = $book['isbn10'];
            $this->isbn13 = $book['isbn13'];
            $this->authorName = $book['authorName'];
            $this->publicationType = $book['publicationType'];
            $this->listPrice = $book['listPrice']; 
            $this->save();
        }
    }
    
    /**
    * converts a row to a book obj
    * 
    * @param mixed $bookArrayItem
    * @return Book
    */
    public function arrayToBook($bookArrayItem) {
        if(!empty($bookArrayItem)){
            $book = new Book();
            $book->id = $bookArrayItem['id']; 
            $book->title = $bookArrayItem['title'];
            $book->isbn10 = $bookArrayItem['isbn10'];
            $book->isbn13 = $bookArrayItem['isbn13'];
            $book->authorName = $bookArrayItem['author_name'];
            $book->publicationType = $bookArrayItem['publication_type'];
            $book->listPrice = $bookArrayItem['list_price']; 
            return $book;
        }
    }
}