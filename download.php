<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'election';

$conn = mysqli_connect($servername, $username, $password, $db);

if (isset($_POST['submit'])){
    opendir('D:\\');
    if(isset($_POST['states'])){    
        //getting value from state selection
        $data = $_POST['states'];
        //getting value from district selection
        }
    if(isset($_POST['district'])){
        $data2 = $_POST['district'];
        }
    if (isset($_POST['num'])){
        $data3 = $_POST['num'];
    }
    if (isset($_POST['lang'])){
        $data4 = $_POST['lang'];
    }
    // download for only states
    if ((!empty($data)) and (empty($data2))){
        if ($data3 == 'Top Three') and ($data4 == "English"){
            $new_state = 'State -' .$data;
            mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT * into OUTFILE 'D:\\State -$data top three.csv' FIELDS TERMINATED by ','  LINES TERMINATED by '\n'  from (select constituencyname, stateno, candidate, votes, partyname from result where (select count(*) from result as f where f.constituencyname = result.constituencyname and f.stateno = '1' and f.votes >= result.votes) <= 3)AS T where stateno = 1";
            mysqli_query($conn, $sql);
            echo "Successfully downloaded State- " .$data . " database (Top Three)";
        }
        elseif ($data3 == 'Winners'){
            $new_state = 'State -' .$data;
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT * into OUTFILE 'D:\\State -$data winners only.csv'  FIELDS TERMINATED by ','  LINES TERMINATED by '\n' from (select constituencyname, stateno, candidate, votes, partyname from result where (select count(*) from result as f where f.constituencyname = result.constituencyname and f.stateno = '1' and f.votes >= result.votes) <= 1)AS T where stateno = 1";
            mysqli_query($conn, $sql);
            echo "Downloaded only the winners";  
        }
        elseif($data3 == "All"){
            //top three
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, stateno, candidate, votes, partyname into OUTFILE 'D:\\State -$data full_db.csv'  FIELDS TERMINATED by ','  LINES TERMINATED by '\n' from result where stateno = $data";
            mysqli_query($conn, $sql);
            echo "Downloaded all database of the given state";
        }

    }
    //  download for specific state and specific district
    elseif ((!empty($data)) && (!empty($data2))){
        if ($data3 == 'Top Three'){
            $new_district = strtolower($data2);
            $new_state = 'State -' .$data;
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, districtno, candidate, partyname, votes INTO OUTFILE 'D:\\State - $data District- $data2 top three.csv' FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n' FROM result WHERE stateno='$data' AND districtno='$data2' LIMIT 3";
            mysqli_query($conn, $sql);
            echo "Successfully downloaded results for State- $data District - $data2";
        }
        elseif ($data3 == 'Winners'){
            $new_state = 'State -' .$data;
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, districtno, candidate, partyname, votes INTO OUTFILE 'D:\\State - $data District- $data2 winner.csv' FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n' FROM result WHERE stateno='$data' AND districtno='$data2' LIMIT 1";
            mysqli_query($conn, $sql);
            echo "Downloaded only the winners from specific selection";
        }
        //for top three
        elseif ($data3=="All"){
            $new_state = 'State -' .$data;
            $new_district = $data2;
            $name = $new_state ." " .$new_district ." top three";
            //sql query goes here
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, districtno, candidate, partyname, votes INTO OUTFILE 'D:\\State - $data District- $data2 all.csv' FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n' FROM result WHERE stateno='$data' AND districtno='$data2'";
            mysqli_query($conn, $sql);
            echo "Downloaded all database of given state and district";
        }
    }
    // download for all results with three choices
    elseif(empty($data) && empty($data2)){
        if ($data3 == 'Winners'){
            $new_state = 'full_db winners only';
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, stateno, candidate, votes, partyname INTO OUTFILE 'D:\\Federal Election 2074 winner only.csv'  FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n' FROM result WHERE votes = (SELECT max(votes) FROM result AS f WHERE f.constituencyname = result.constituencyname) or votes = (SELECT max(votes) FROM result AS f WHERE f.constituencyname = result.constituencyname AND votes > (SELECT max(votes) FROM result AS f2 WHERE f2.constituencyname = result.constituencyname))"; 
            mysqli_query($conn, $sql);
            echo "downloaded all results -winners only";
        }
        elseif($data3 == "All"){
            $new_state = 'State -' .$data;
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, districtno, candidate, partyname, votes INTO OUTFILE 'D:\\Federal Election 2074 full result.csv' FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'  FROM result";
            mysqli_query($conn, $sql);
            echo "Successfully downloaded all database";  
        }
        elseif ($data3=="Top Three"){
            $new_state = 'Full_db top three ';
            $res = mysqli_query($conn, "SET NAMES utf8");
            $sql = "SELECT constituencyname, stateno, candidate, votes, partyname into OUTFILE 'D:\\Federal Election 2074 top three.csv'  FIELDS TERMINATED by ','  LINES TERMINATED by '\n' from result where (select count(*) from  result as f where f.constituencyname = result.constituencyname and f.votes >= result.votes) <= 3";
            
            if (mysqli_query($conn, $sql)){
                echo "This is working";
            }
            else{
                echo "Download Error";
            }
            //download for top three
        }
       
       }
    }


?>
<?php
// <!-- display -->
// $sql = "SELECT * FROM result";
// $name = mysqli_query($conn, "SET NAMES utf8");
// $result = mysqli_query($conn, $sql);
// echo "<table>
// <tr>
// <th>Stateno</th>
// <th>Constituencyname</th>
// <th>Candidate</th>
// <th>Partyname</th>
// <th>Votes</th>
// </tr>";
// while($row = mysqli_fetch_array($result)) {
//     echo "<tr>";
//     echo "<td>" . $row['stateno'] . "</td>";
//     echo "<td>" . $row['constituencyname'] . "</td>";
//     echo "<td>" . $row['candidate'] . "</td>";
//     echo "<td>" . $row['partyname'] . "</td>";
//     echo "<td>" . $row['votes'] . "</td>";
//     echo "</tr>";
// }
// echo "</table>";
// mysqli_close($con);
?>
<!-- bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<!-- js -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<body class="bg-primary">
    <h1 class="display-4 text-center text-white">Download Results for Federal Election Of Nepal 2074</h1>

<div class="container">
<form action="" method="post" style="margin-top:15px;">
<div class="form-row">
<!-- DROPDOWN -->
    <div class="col-md-3">
        <select id="states" name="states" class="custom-select" onchange="sendState(this.value)">
            <option value="">States</option>
            <option value="1">State - 1</option>
            <option value="2">State - 2</option>
            <option value="3">State - 3</option>
            <option value="4">State - 4</option>
            <option value="5">State - 5</option>
            <option value="6">State - 6</option>
            <option value="7">State - 7</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="districts" name="district" class="custom-select" onchange="sendDistrict(this.value)">
            <option value="">Districts</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="num" id="num" class="custom-select">
            <option value="All">All</option>
            <option value="Top Three">Top Three</option>
            <option value="Winners">Winners(Top 1)</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="lang" id="lang" class="custom-select">
            <option value="English">English</option>
            <option value="Nepali">Nepali</option>
        </select>
    </div>
    <div class="col-md-1">
    <div class="text-center" style="margin-top: 15px;">
            <input type='submit' name='submit' class="btn btn-md btn-danger" value='Download' />
    </div>          
    </div>
</div>

</form>
</div>


<!-- button download -->

<!-- Search Area JavaScript -->
<script type="text/javascript">
    // $(document).ready(function () {
    $("#states").change(function () {
        $("#districts option").remove();
        var el = $(this);
        if (el.val() === "1") {
            pd("", 0);
            pd("Bhojpur", 1);
            pd("Dhankuta", 2);
            pd("Ilam", 3);
            pd("Jhapa", 4);
            pd("Khotang", 5);
            pd("Morang", 6);
            pd("Okhaldhunga", 7);
            pd("Sankhuwasabha", 8);
            pd("Solukhumbu", 9);
            pd("Sunsari", 10);
            pd("Taplejung", 11);
            pd("Tehrathum", 12);
            pd("Udayapur", 13);
            pd("Panchthar", 14);
        }
        if (el.val() === "2") {
            pd("", 0);
            pd("Bara", 15);
            pd("Dhanusa", 16);
            pd("Mahottari", 17);
            pd("Parsa", 18);
            pd("Rautahat", 19);
            pd("Saptari", 20);
            pd("Sarlahi", 21);
            pd("Siraha", 22);
        }
        if (el.val() === "3") {
            pd("", 0);
            pd("Bhaktapur", 23);
            pd("Chitwan", 24);
            pd("Dhading", 25);
            pd("Dolakha", 26);
            pd("Kathmandu", 27);
            pd("Kavrepalanchok", 28);
            pd("Lalitpur", 29);
            pd("Makwanpur", 30);
            pd("Nuwakot", 31);
            pd("Ramechhap", 32);
            pd("Rasuwa", 33);
            pd("Sindhuli", 34);
            pd("Sindhupalchok", 35);
        }
        if (el.val() === "4") {
            pd("", 0);
            pd("Baglung", 36);
            pd("Gorkha", 37);
            pd("Kaski", 38);
            pd("Lamjung", 39);
            pd("Manang", 40);
            pd("Mustang", 41);
            pd("Myagdi", 42);
            pd("Nawalparasi East", 43);
            pd("Parbat", 44);
            pd("Syangja", 45);
            pd("Tanahu", 46);
        }
        if (el.val() === "5") {
            pd("", 0);
            pd("Arghakhanchi", 47);
            pd("Banke", 48);
            pd("Bardiya", 49);
            pd("Dang", 50);
            pd("Gulmi", 51);
            pd("Kapilvastu", 52);
            pd("Nawalparasi West", 53);
            pd("Palpa", 54);
            pd("Pyuthan", 55);
            pd("Rolpa", 56);
            pd("Rukum East", 57);
            pd("Rupandehi", 58);
        }
        if (el.val() === "6") {
            pd("", 0);
            pd("Dailekh", 59);
            pd("Dolpa", 60);
            pd("Humla", 61);
            pd("Jajarkot", 62);
            pd("Jumla", 63);
            pd("Kalikot", 64);
            pd("Mugu", 65);
            pd("Surkhet", 66);
            pd("Rukum West", 67);
            pd("Salyan", 68);
        }
        if (el.val() === "7") {
            pd("", 0);
            pd("Achham", 69);
            pd("Baitadi", 70);
            pd("Bajhang", 71);
            pd("Bajura", 72);
            pd("Dadeldhura", 73);
            pd("Darchula", 74);
            pd("Doti", 75);
            pd("Kailali", 76);
            pd("Kanchanpur", 77);
        }

        // pd = print district
        function pd(district, value) {
            var phtml = "<option " + "value='" + value + "'>" + district + "</option>";
            return $("#districts").append(phtml);
        }
    });
    // });
</script>
 </body>