<!DOCTYPE html>
<html>
    <head>
        <title>Book Flight</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include 'navigation.php'; ?>
        <h2 style="margin-left: 20px">Book Flight</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionUser = $_SESSION['username'];
            $servername = "mysql:host=localhost;dbname=air_ds";
            $username = "root";
            $password = "";
            $from = $_POST['from'];
            $where = $_POST['where'];
            $time = $_POST['time'];
            $count = $_POST['count'];
            try { //Get user's full name to be already filled
                $conn = new PDO($servername, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT name,surname FROM users WHERE username = :username");
                $stmt->bindParam(':username', $sessionUser);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            try { //Get info from departure airport to calculate cost
                $conn = new PDO($servername, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT latitude,longitude,tax FROM airports WHERE name = :from");
                $stmt->bindParam(':from', $from);
                $stmt->execute();
                $fromResult = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            try { //Get info from arrival airport to calculate cost
                $conn = new PDO($servername, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT latitude,longitude,tax FROM airports WHERE name = :where");
                $stmt->bindParam(':where', $where);
                $stmt->execute();
                $whereResult = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            try { //Get already reserved seats from selected flight and put them in an array
                $conn = new PDO($servername, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT seats FROM reservations WHERE departure = :departure AND arrival = :arrival AND date = :date");
                $stmt->bindParam(':departure', $from);
                $stmt->bindParam(':arrival', $where);
                $stmt->bindParam(':date', $time);
                $stmt->execute();
                $seatsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $allSeats = [];
                foreach ($seatsResult as $row) {
                    $seats = explode(',', $row['seats']);
                    $allSeats = array_merge($allSeats, $seats);
                }
                $seatsResult = $allSeats;
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        ?>
        <form id="names" style="margin-left: 20px" action="booked.php" method="POST"> <!-- Names Section -->
            <label>Your Name: </label>
            <input type="text" name="names[]" value="<?= htmlspecialchars($result['name']) ?>" readonly></br>
            <label>Your Surname: </label>
            <input type="text" name="surnames[]" value="<?= htmlspecialchars($result['surname']) ?>" readonly><br><br>
                <?php
                    for ($x = 2; $x <= $count; $x++) {
                        echo "<label>Passenger " . $x . " Name: </label>";
                        echo '<input type="text" name="names[]" pattern="[A-Za-z]{3,20}" required><br>';
                        echo "<label>Passenger " . $x . " Surname: </label>";
                        echo '<input type="text" name="surnames[]" pattern="[A-Za-z]{3,20}" required><br>';
                        echo "<spam>It must only contain latin characters and the length be between 3 to 20 characters.</spam><br><br>";
                    }
                ?>
            <h2>Select Seats</h2>
            <div id="seatsView" style="display: none; flex-wrap: wrap;"> <!-- Airplane Seats Sections -->
            </div>
            <div id="seatsSelected"> <!-- Selected Seats Sections -->
            </div>
            <h2 id="cost" style="display:none">Cost</h2>
            <div id="ticketsCost"> <!-- Tickets Sections -->
            </div>
            <input style="display:none" type="text" id="username" name="username" value="<?= htmlspecialchars($sessionUser) ?>"></input>
            <input style="display:none" type="text" id="departure" name="departure" value="<?= htmlspecialchars($from) ?>"></input>
            <input style="display:none" type="text" id="arrival" name="arrival" value="<?= htmlspecialchars($where) ?>"></input>
            <input style="display:none" type="date" id="date" name="date" value="<?= htmlspecialchars($time) ?>"></input>
            <input style="display:none" type="number" id="passengers" name="passengers" value="<?= htmlspecialchars($count) ?>"></input>
            <input style="display:none" type="text" id="tax" name="tax"></input>
            <input style="display:none" type="submit" id="book" value="Book Flight"></input>
        </form>

        <script>
            const bookSubmit = document.getElementById("book");
            const names = document.getElementById("names");
            const seatsView = document.getElementById("seatsView");
            const cost = document.getElementById("cost");
            const seatsSelected = document.getElementById("seatsSelected");
            const ticketsCost = document.getElementById("ticketsCost");
            names.addEventListener("input",checkNames);
            if(<?= $count ?> == 1) seatsView.style.display="flex"; //Check if names are valid to show airplane seats
            function checkNames() {
                if (names.checkValidity()) {
                    seatsView.style.display="flex";
                }
                else seatsView.style.display="none";
            }
            const letters = ["F", "E", "D", "C", "B", "A"];
            const takenSeats = <?= json_encode($seatsResult); ?>;
            for (let i = 0; i <= 5; i++) { //Create airplane rows
                if(i==3) {
                    const row = document.createElement("div");
                    row.className = "numbers";
                    row.innerText = "#";
                    seatsView.appendChild(row);
                    for (let i = 1; i <= 31; i++) {
                        const seat = document.createElement("button");
                        seat.className = "number";
                        seat.innerText = i;
                        seat.type = "button";
                        row.appendChild(seat);
                    }
                }
                const row = document.createElement("div"); //Create airplane columns
                row.className = "row";
                row.innerText = letters[i];
                seatsView.appendChild(row);
                for (let j = 1; j <= 31; j++) {
                    const seat = document.createElement("button");
                    seat.id = letters[i] + j;
                    seat.className = "seat";
                    if (takenSeats.includes(seat.id)) { //Check if seat is taken
                        seat.classList.toggle("taken");
                        seat.disabled = true;
                    }
                    seat.type = "button";
                    row.appendChild(seat);
                }
            }
            const seats = document.querySelectorAll(".seat");
            let selected = <?php echo $count; ?>;
            seats.forEach(button => { //When seat is clicked
                button.addEventListener("click", () => {
                    if(button.classList.contains("selected")) { //If seat is already selected, unselect it
                        selected++;
                        button.classList.toggle("selected");
                        seatsSelected.removeChild(seatsSelected.lastChild);
                        seatsSelected.removeChild(seatsSelected.lastChild);
                        seatsSelected.removeChild(seatsSelected.lastChild);
                    }
                    else {
                        if(selected > 0) { //If seat is open, select it and show seat selected
                            selected--;
                            button.classList.toggle("selected");
                            const selection = document.createElement("label");
                            selection.name = "seats[]";
                            selection.innerHTML = "Seat Selected: ";
                            seatsSelected.appendChild(selection);
                            const selectionInput = document.createElement("input");
                            selectionInput.type = "text";
                            selectionInput.name = "seats[]";
                            selectionInput.value = button.id;
                            selectionInput.readOnly = true
                            seatsSelected.appendChild(selectionInput);
                            seatsSelected.appendChild(document.createElement("br"));
                        }
                        else if(selected==0) return; //If you can't click more open seats, return
                    }
                    if(selected==0) { //If selected required seats, show details
                        cost.style.display="block";
                        const inputs = seatsSelected.querySelectorAll('input');
                        var no = 0;
                        var total = 0;
                        inputs.forEach(input => { //For each seat calculate cost and show it
                            no++;
                            const value = input.value;
                            const number = value.slice(1);
                            let cost = 0;
                            if(number == 1 || number == 11 || number == 12) cost = 20;
                            else if(number>=2 && number <=10) cost = 10;
                            const tax = <?= htmlspecialchars($fromResult['tax']) + htmlspecialchars($whereResult['tax']) ?>;
                            const d = [<?= htmlspecialchars($fromResult['latitude'])?>,<?= htmlspecialchars($whereResult['latitude'])?>,<?= htmlspecialchars($fromResult['longitude'])?>,<?= htmlspecialchars($whereResult['longitude'])?>];
                            function toRad(degree) {
                                return degree * Math.PI / 180;
                            }
                            const lat1 = toRad(d[0]);
                            const lon1 = toRad(d[2]);
                            const lat2 = toRad(d[1]);
                            const lon2 = toRad(d[3]);
                            const { sin, cos, sqrt, atan2 } = Math;
                            const dLat = lat2 - lat1;
                            const dLon = lon2 - lon1;
                            const a = sin(dLat / 2) * sin(dLat / 2) + cos(lat1) * cos(lat2) * sin(dLon / 2) * sin(dLon / 2);
                            const c = 2 * atan2(sqrt(a), sqrt(1 - a)); 
                            const distance = Math.floor(6371 * c / 10);
                            const ticket = tax + distance + cost;
                            total += ticket;
                            const ticketCost = document.createElement("label");
                            ticketCost.name = "costs[]";
                            ticketCost.innerHTML = "Ticket " + no + " cost: ";
                            ticketsCost.appendChild(ticketCost);
                            const ticketCostInput = document.createElement("input");
                            ticketCostInput.type = "text";
                            ticketCostInput.name = "costs[]";
                            ticketCostInput.value = ticket;
                            ticketCostInput.readOnly = true
                            ticketsCost.appendChild(ticketCostInput);
                            ticketsCost.appendChild(document.createElement("br"));
                        });
                        //Show final info before booking
                        const totalCost = document.createElement("p");
                        totalCost.innerHTML = "<br><br>" + "Total cost: " + total + "€<br>";
                        ticketsCost.appendChild(totalCost);
                        const showFrom = document.createElement("p");
                        showFrom.innerHTML = "Departure: " + <?= json_encode(htmlspecialchars($from)) ?> + "<br>";
                        ticketsCost.appendChild(showFrom);
                        const showWhere = document.createElement("p");
                        showWhere.innerHTML = "Arrival: " + <?= json_encode(htmlspecialchars($where)) ?> + "<br>";
                        ticketsCost.appendChild(showWhere);
                        const showDate = document.createElement("p");
                        showDate.innerHTML = "Date: " + <?= json_encode(htmlspecialchars($time)) ?> + "<br><br>";
                        ticketsCost.appendChild(showDate);
                        document.getElementById("tax").value = <?= htmlspecialchars($fromResult['tax']) + htmlspecialchars($whereResult['tax']) ?>;
                        bookSubmit.style.display = "block";
                    }
                    else { //If required seats not selected, don't show anything
                        bookSubmit.style.display = "none";
                        cost.style.display="none";
                        ticketsCost.innerHTML = "";
                    }
                });
            });
        </script>
    </body>
    <footer>
        <?php include 'footer.html'; ?>
    </footer>
</html>