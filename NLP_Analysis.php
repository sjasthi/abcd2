<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLP Data Table</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Include Compromise.js -->
    <script src="https://unpkg.com/compromise@11.12.0"></script>

    <!-- Include DataTables CSS and JS files -->
    <link href="css/list_dresses.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Include DataTables JS file -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- Include DataTables Buttons extension -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.6.2/css/buttons.dataTables.min.css">
    <script type="text/javascript" language="javascript" 
			src="https://cdn.datatables.net/buttons/2.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" 
			src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" 
			src="https://cdn.datatables.net/buttons/2.6.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" charset="utf8"
			src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
</head>
<body>

    <h2>NLP Data Table</h2>

    <table id="NLPTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Keywords</th>
                <th>Description Word Count</th>
                <!--<th>Desc. Noun Count</th>
                <th>Desc. Adjective Count</th>-->
				<th>Did You Know Word</th>
                <th>Did You Know Word Count</th>
                <!--<th>DYK Noun Count</th>
                <th>DYK Adjective Count</th>-->
                <th>Total Word Count</th>
                <!-- <th>Total Noun Count</th>
                <th>Total Adjective Count</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            include('header.php');
            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "abcd_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Assuming your table is named 'dresses', replace it with your actual table name
            $sql = "SELECT ID, Name, description, key_words, did_you_know FROM dresses";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>$cell</td>";
                    }

                    // Count the number of words in the "description" column
                    $descriptionCount = str_word_count($row['description']);
                    echo "<td>$descriptionCount</td>";

                    /* // Count the number of nouns in the "description" column using Compromise.js
                    $descNounCount = getNounCount($row['description']);
                    echo "<td>$descNounCount</td>";

                    // Count the number of adjectives in the "description" column using Compromise.js
                    $descAdjectiveCount = getAdjectiveCount($row['description']);
                    echo "<td>$descAdjectiveCount</td>"; */

                    // Count the number of words in the "dyk_description" column
                    $dykWordCount = str_word_count($row['did_you_know']);
                    echo "<td>$dykWordCount</td>";

                    /* // Count the number of nouns in the "dyk_description" column using Compromise.js
                    $dykNounCount = getNounCount($row['did_you_know']);
                    echo "<td>$dykNounCount</td>";

                    // Count the number of adjectives in the "dyk_description" column using Compromise.js
                    $dykAdjectiveCount = getAdjectiveCount($row['did_you_know']);
                    echo "<td>$dykAdjectiveCount</td>"; */

                    // Calculate total word count
                    $totalWordCount = $descriptionCount + $dykWordCount;
                    echo "<td>$totalWordCount</td>";

                   /* // Calculate total noun count
                    $totalNounCount = $descNounCount + $dykNounCount;
                    echo "<td>$totalNounCount</td>";

                    // Calculate total adjective count
                    $totalAdjectiveCount = $descAdjectiveCount + $dykAdjectiveCount;
                    echo "<td>$totalAdjectiveCount</td>"; */

                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }

            $conn->close();

            // Function to get noun count using Compromise.js
            function getNounCount($text) {
                $doc = nlp($text);
                $nouns = $doc->nouns()->out('array');
                return count($nouns);
            }

            // Function to get adjective count using Compromise.js
            function getAdjectiveCount($text) {
                $doc = nlp($text);
                $adjectives = $doc->adjectives()->out('array');
                return count($adjectives);
            }
            ?>
        </tbody>
    </table>

    <script type="text/javascript" language="javascript">
        $(document).ready(function () {
            
            var table = $('#NLPTable').DataTable({
                dom: 'lfrtBip',
                buttons: [
                    'copy', 'excel', 'csv', 'pdf'
                ]
            });

            
            $('#NLPTable thead tr').clone(true).appendTo('#NLPTable thead');
            $('#NLPTable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');

                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });
    </script>
</body>
</html>
