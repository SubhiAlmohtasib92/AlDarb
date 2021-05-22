        	<div style="width:100%;">
<canvas id="myChart" width="300" height="300"></canvas>


	</div>
    
    
    

<script>var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Collection',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgba(255, 99, 132,.6)',
            data: [0, 10, 5, 2, 20, 30, 45]
        },{
            label: 'Destination',
            backgroundColor: 'rgb(10, 199, 132)',
            borderColor: 'rgb(50, 159, 12)',
            data: [0, 60, 4, 2, 20, 30, 45]
        }]
    },

    // Configuration options go here
    options: {}
});
</script>
    
    
    
    