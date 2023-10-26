<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Calculator</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .output-section {
            margin-top: 20px;
        }
        #loadingSpinner {
            margin: 0 auto;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="jumbotron text-center shadow-sm p-3 mb-5 bg-body rounded">
            <p class=" h1">Age Calculator!</p>
            <p class="lead">Copyright © Alemam - <?php echo date("Y");?></p>
        </div>

        <div class="row">
            <div class="col-xl-5 col-lg-4 col-sm-6 mx-auto">
                <form id="ageCalculatorForm">
                    <div class="form-group">
                        <label for="birthdate" class="mb-2">Your Birthdate:</label>
                        <input type="date" class="form-control mb-4" id="birthdate" required>
                    </div>
                    <div class="form-group">
                        <label for="another_date" class="mb-2">Age at the Date of:</label>
                        
                        <input type="date" class="form-control mb-4" id="another_date" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="calculateAge()">Calculate Age</button>
                </form>
                
                <div class="spinner-border text-primary mt-5" role="status" id="loadingSpinner" style="display: none;">
                    <span class="sr-only">Loading...</span>
                </div>

                <div class="output-section mt-4 shadow-sm p-3 mb-5 bg-body rounded" id="outputSection" style="display: none;">
                    
                </div>
            </div>
        </div>
        
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        
        function calculateAge() {
            document.getElementById('outputSection').style.display = "none";
            document.getElementById("loadingSpinner").style.display = "block";

            let dateOfBirth = document.getElementById('birthdate').value;
            let anotherDate = document.getElementById('another_date').value;
            // Data to be sent in the POST request
            let data = {
                date_of_birth: dateOfBirth,
                another_date: anotherDate
            };

            console.log(data);

            setTimeout(function () {
            fetch('calculate-age.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                date_of_birth: dateOfBirth,
                another_date: anotherDate
            })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log the response for debugging

                if(data.status === "success"){
                    let age = "";

                    if(data.age.years > 0){
                        age+=" "+data.age.years+" years";
                    }
                    if(data.age.months > 0){
                        age+=" "+data.age.months+" months";
                    }
                    if(data.age.days > 0){
                        age+=" "+data.age.days+" days";
                    }
                    if(data.age.years == 0 && data.age.months == 0 && data.age.days == 0){
                        age+="Both are same dates";
                    }
                    document.getElementById("loadingSpinner").style.display = "none";
                    document.getElementById('outputSection').style.display = "block";
                    document.getElementById('outputSection').innerHTML = 'Calculated Age:<br>' + age;
                    // document.getElementById('outputSection').innerHTML = 'Calculated Age:<br>' + age + "<br>"+data.age.weeks+ " weeks<br>"+data.age.hours.toLocaleString('en-US')+ " hours<br>"+data.age.minutes.toLocaleString('en-US')+ " minutes<br>"+data.age.seconds.toLocaleString('en-US')+" seconds";
                    document.getElementById('outputSection').style = '';
                }
                else{
                    document.getElementById("loadingSpinner").style.display = "none";
                    document.getElementById('outputSection').style.display = "block";
                    document.getElementById('outputSection').innerHTML = data.message + "<br>Date of birth needs to be earlier than the age at date.";
                    document.getElementById('outputSection').style.color="red";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, 2000);
        }
    </script>
</body>

</html>
