<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kahaer ICA04</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="code.js"></script>
</head>
<body>
    <header>
        Assignment 04 - mySQL Data Manipulation
    </header>
    
    <section id="authorTable">
    </section>

    <hr>
    
    <div>
    </div>

    <hr>
    <section>
            <table>
                <tr>
                    <td class="tbtext">Title ID:</td>
                    <td><input type="text" name="bookID" id="bookID" placeholder="Supply the Books ID"></td>
                </tr>
                <tr>
                    <td class="tbtext">Title:</td>
                    <td><input type="text"  name="bookTitle" id="bookTitle"  placeholder="Supply the Book's Name"></td>
                </tr>
                <tr>
                    <td class="tbtext">Type:</td>
                    <td id="putGenreHere">

                    </td>
                </tr>
                <tr>
                    <td class="tbtext">Price:</td>
                    <td><input type="text" name="bookPrice" id="bookPrice" placeholder="Supply the Book's Cost"></td>
                </tr>
                <tr>
                    <td class="tbtext">Authors:</td>
                    <td id="putAuthorsHere">

                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button name="addBook" id="addBook">Add Book</button></td>
                </tr>
            </table>
    </section>


    <footer>
        <script>
            document.write('<p><hr><small><i>Last modified: ' + document.lastModified + '</i></small>');
        </script>
        KahaerMadeIt LLC.
    </footer>
</body>
</html>