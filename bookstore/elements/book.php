    <div class="bookContainer">
    <?php
        if ($add || $edit) {
    ?>
            <form action="?" method="post">
                <?php if($edit) { ?>
                <input type="hidden" name="id" value="<?php echo $book->id ?>" />
                <?php } ?>
                <p>
                    <label for="title">Title:</label>
                    <input name="title" type="text" size="25" value="<?php echo (empty($_POST['title'])? $book->title : $_POST['title']) ?>"/>
                </p>
                <p>
                    <label for="isbn10">ISBN 10:</label>
                    <input name="isbn10" type="text" size="25"value="<?php echo (empty($_POST['isbn10'])? $book->isbn10 : $_POST['isbn10']) ?>"/>
                </p>
                <p>
                    <label for="isbn13">ISBN 13:</label>
                    <input name="isbn13" type="text" size="25" value="<?php echo (empty($_POST['isbn13'])? $book->isbn13 : $_POST['isbn13']) ?>"/>
                </p>
                <p>
                    <label for="authorName">Author:</label>
                    <input name="authorName" type="text" size="25" value="<?php echo (empty($_POST['authorName'])? $book->authorName : $_POST['authorName']) ?>"/>
                </p>
                <p>
                    <label for="publicationType">Publication:</label>
                    <input name="publicationType" type="text" size="25" value="<?php echo (empty($_POST['publicationType'])? $book->publicationType : $_POST['publicationType']) ?>"/>
                </p>
                <p>
                    <label for="listPrice">List Price:</label>
                    <input name="listPrice" type="text" size="25" value="<?php echo (empty($_POST['listPrice'])? $book->listPrice : $_POST['listPrice']) ?>"/>
                </p>
                <p class="submit"><input type="submit" value="Save" /></p>
            </form>
    <?php        
        } else { ?>      
            <p>Title: <b><?php echo $book->title ?></b></p>
            <?php 
                if((int) $book->id > 0){
                    echo "<a href='?a=edit&id=".$book->id."'>edit</a>";
                } 
            ?>
            <p>Isbn 10: <?php echo $book->isbn10 ?></p>
            <p>Isbn 13: <?php echo $book->isbn13 ?></p>
            <p>Author: <?php echo $book->authorName ?></p>
            <p>Publication: <?php echo $book->publicationType ?></p>
            <p>List Price: <?php echo $book->listPrice ?></p>
    <?php        
        }
    ?>
    </div>