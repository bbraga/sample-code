<?php
/**
* March 30th, 2011
* @author mail@brunobraga.eu 
* @copyright Bruno Braga - 2011 
* Purpose, accesses valorebooks.com and scrapes data from the site
*/    
class BookRetriever {
    const bookUrl = 'http://www.valorebooks.com/top-textbooks';    
    
    public function BookRetriever() {
        require("simple_html_dom.php");
    }
    
    /**
    * Function that retrieves the html parses it and returns a book recursive array list
    * 
    * @returns array books
    */
    public function getBookData() {
        $books = $this->parseBooksHtml($htmlData);
        return $books;
    }
    
    /**
    * Parses the html data retrieved remotely and returns a recursive assoc book array
    * 
    * @param mixed $htmlData
    * @return mixed
    */
    private function parseBooksHtml($htmlData){
        $html = file_get_html(BookRetriever::bookUrl);
        $books = array();
        foreach($html->find('div.result_listing') as $book) {
            $item = array();
            $item['title'] = $book->find('a.product_title', 0)->plaintext;
            $item['isbn10'] = str_ireplace('ISBN-10: ','',$book->find('span.results_isbn', 1)->plaintext);
            $item['isbn13'] = str_ireplace('ISBN-13: ','',$book->find('span.results_isbn', 0)->plaintext);
            $item['authorName'] = str_ireplace('by ','',$book->find('span.by', 0)->plaintext);
            $item['publicationType'] = $book->find('span.results_book_type', 0)->plaintext;
            $item['listPrice'] = str_ireplace('List Price: ','',$book->find('span.results_list_price', 0)->plaintext);
            $books[] = $item;
        }
        return $books;
    }
}