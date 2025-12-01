<?php 
include('includes/checklogin.php');
check_login();

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
	<div class="container-scroller">
		<!-- partial:partials/_navbar.html -->
		<?php @include("includes/header.php");?>
		<!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<!-- partial:partials/_sidebar.html -->
			<?php @include("includes/sidebar.php");?>
			<!-- partial -->
			<div class="main-panel">
				<div class="content-wrapper">
					<div class="row">
						<div class="col-md-4 stretch-card grid-margin">
							<div class="card bg-gradient-danger card-img-holder text-white"style="height: 150px;">
								<div class="card-body">
									<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
									<h4 class="font-weight-normal mb-3">Weekly bankings <i class="mdi mdi-chart-line mdi-24px float-right"></i>
									</h4>
									<?php
                                    //Last Sevendays Sale
									$query8=mysqli_query($conn,"select *
										from banks  where date(transaction_date)>=(DATE(NOW()) - INTERVAL 7 DAY);");
									while($row8=mysqli_fetch_array($query8))
									{
										$sevendays_sale=$row8['transaction_amount'];
										$tseven+=$sevendays_sale;

									}
									?>
									<h2 class="mb-5">
										<?php
										$pop2=0;
										if (strlen($tseven==0)) {
											echo $pop2;
										} else{ 
											echo htmlentities(number_format($tseven, 0, '.', ','));
										}
										?> </h2>
									</div>
								</div>
							</div>
							<div class="col-md-4 stretch-card grid-margin">
								<div class="card bg-gradient-info card-img-holder text-white"style="height: 150px;">
									<div class="card-body">
										<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
										<h4 class="font-weight-normal mb-3">Petty Cash Balance <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
										</h4>
										<?php
                                        //petty cash balance
										$query3=mysqli_query($conn,"select *
											from petty_balance ");
										while($row3=mysqli_fetch_array($query3))
										{
											$petty_balance=$row3['balance'];
											$pettycash+=$petty_balance;

										}
										?>
										<h2 class="mb-5"><?php echo htmlentities(number_format($pettycash, 0, '.', ','));?></h2>
									</div>
								</div>
							</div>
							<div class="col-md-4 stretch-card grid-margin">
								<div class="card bg-gradient-success card-img-holder text-white"style="height: 150px;">
									<div class="card-body">
										<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
										<h4 class="font-weight-normal mb-3">Total bank amount <i class="mdi mdi-diamond mdi-24px float-right"></i>
										</h4>
										<?php
                                        //Total Sale
										$query9=mysqli_query($conn,"select *
											from banks ");
										while($row9=mysqli_fetch_array($query9))
										{
											$total_sale=$row9['transaction_amount'];
											$totalsale+=$total_sale;

										}
										?>
										<h2 class="mb-5"><?php echo htmlentities(number_format($totalsale, 0, '.', ','));?></h2>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 stretch-card grid-margin">
								<div class="card bg-gradient-info card-img-holder text-white"style="height: 150px;">
									<div class="card-body">
										<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
										<h4 class="font-weight-normal mb-3">Total Male Christians <i class="mdi mdi-chart-line mdi-24px float-right"></i>
										</h4>
										<?php $query4=mysqli_query($conn,"Select * from tblchristian where Sex='Male'");
										$totalmale=mysqli_num_rows($query4);
										?>
										<h2 class="mb-5"> <?php echo $totalmale;?></h2>
									</div>
								</div>
							</div>
							<div class="col-md-4 stretch-card grid-margin">
								<div class="card bg-gradient-info card-img-holder text-white"style="height: 150px;">
									<div class="card-body">
										<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
										<h4 class="font-weight-normal mb-3">Total Female Christians <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
										</h4>
										<?php $query5=mysqli_query($conn,"Select * from tblchristian where Sex='Female'");
										$totalfemale=mysqli_num_rows($query5);
										?>
										<h2 class="mb-5"><?php echo $totalfemale;?></h2>
									</div>
								</div>
							</div>
							<div class="col-md-4 stretch-card grid-margin">
								<div class="card bg-gradient-info card-img-holder text-white"style="height: 150px;">
									<div class="card-body">
										<img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
										<h4 class="font-weight-normal mb-3">Total Christians <i class="mdi mdi-diamond mdi-24px float-right"></i>
										</h4>
										<?php $query6=mysqli_query($conn,"Select * from tblchristian");
										$totalchristians=mysqli_num_rows($query6);
										?>
										<h2 class="mb-5"><?php echo $totalchristians;?></h2>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<div class="clearfix">
											<h4 class="card-title float-left">Church Expenses</h4>
											<div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
										</div>
										<canvas id="graphCanvas4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="col-md-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Church Population</h4>
										<canvas id="graphCanvas2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
										<div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
									</div>
								</div>
							</div>
						</div> -->
						<div class="row">
							<div class="col-12 grid-margin">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Recent Promises</h4>
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th> Assignee </th>
														<th> Promised Amount </th>
														<th> Paid Amount</th>
														<th> Balance</th>
														<th> Status</th>
														<th> Last Update </th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<img src="assets/images/faces/face1.jpg" class="mr-2" alt="image"> Tom Grey
														</td>
														<td> 800,000</td>
														<td> 800,000</td>
														<td> 0</td>
														<td>
															<label class="badge badge-gradient-success">DONE</label>
														</td>
														<td>May 15, 2021 </td>
													</tr>
													<tr>
														<td>
															<img src="assets/images/faces/face2.jpg" class="mr-2" alt="image"> Stella Mercy 
														</td>
														<td> 500,000</td>
														<td> 300,000</td>
														<td> 200,000</td>
														<td>
															<label class="badge badge-gradient-warning">PROGRESS</label>
														</td>
														<td> May 15, 2021</td>
													</tr>
													<tr>
														<td>
															<img src="assets/images/faces/face3.jpg" class="mr-2" alt="image"> Morgan Michel
														</td>
														<td> 1,000,000</td>
														<td> 600,000</td>
														<td>400,000 </td>
														<td>
															<label class="badge badge-gradient-info">PROGRESS</label>
														</td>
														<td>May 8, 2021</td>
													</tr>
													<tr>
														<td>
															<img src="assets/images/faces/face4.jpg" class="mr-2" alt="image"> Joel Doe 
														</td>
														<td> 400,000</td>
														<td> 400,000</td>
														<td> 0</td>
														<td>
															<label class="badge badge-gradient-danger">COMPLETE</label>
														</td>
														<td> May 3, 2021 </td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- content-wrapper ends -->
					<!-- partial:partials/_footer.html -->
					<?php @include("includes/footer.php");?>
					<!-- partial -->
				</div>
				<!-- main-panel ends -->
			</div>
			<!-- page-body-wrapper ends -->
		</div>
		<!-- container-scroller -->
		<?php @include("includes/foot.php");?>
		<script >

			$(document).ready(function () {
				showGraph2();
			});
			function showGraph2()
			{
				{
					$.post("data.php",
						function (data)
						{
							console.log(data);
							var name = [];
							var marks = [];

							for (var i in data) {
								name.push(data[i].Sex);
								marks.push(data[i].total);
							}

							var chartdata = {
								labels: name,
								datasets: [
								{
									label: 'Student Marks',
									backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            // borderColor: '#46d5f1',
            hoverBackgroundColor: '#CCCCCC',
            hoverBorderColor: '#666666',
            data: marks
        }
        ]
    };

    var graphTarget = $("#graphCanvas2");

    var pieChart = new Chart(graphTarget, {
    	type: 'pie',
    	data: chartdata
    });
});
				}
			}
			$(document).ready(function(){
				$.ajax({
					url: "data1.php",
					method: "GET",
					success: function(data1){
						console.log(data1);
						var name = [];
						var marks = [];

						for (var i in data1){
							name.push(data1[i].expense);

							marks.push(data1[i].amount);
						}
						var chartdata = {
							labels: name,
							datasets: [{
								label: 'Expenses',
								backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
								borderColor: 'rgba(134, 159, 152, 1)',
								hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
								hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
								data: marks

							}]
						};
						var graphTarget = $("#graphCanvas4");
						var barGraph = new Chart(graphTarget, {
							type: 'bar',
							data: chartdata,
							options: {
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: true
										}
									}]
								}
							}
						});
					},
					error: function(data) {
						console.log(data);
					}

				});
			});
		</script>
	</body>
	</html>


