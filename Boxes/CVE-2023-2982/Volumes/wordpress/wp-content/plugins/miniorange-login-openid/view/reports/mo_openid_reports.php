<?php

function mo_openid_reports() {  ?>
<div class = "mo_openid_mywatermark">
	<label for="select_display" style="color: #1c1c1c; text-align: left; font-weight: bold;">SELECT USER TYPE: </label>
	<select id="select_display" disabled>
		<option value="all" selected>All Users</option>
		<option value="social" >Social Login Users</option>
	</select>

	<style>
		.pagination{display:inline-block;padding-left:0;margin:20px 0;border-radius:4px}.pagination>li{display:inline}.pagination>li>a,.pagination>li>span{position:relative;float:left;padding:6px 12px;margin-left:-1px;line-height:1.42857143;color:#337ab7;text-decoration:none;background-color:#fff;border:1px solid #ddd}.pagination>li:first-child>a,.pagination>li:first-child>span{margin-left:0;border-top-left-radius:4px;border-bottom-left-radius:4px}.pagination>li:last-child>a,.pagination>li:last-child>span{border-top-right-radius:4px;border-bottom-right-radius:4px}.pagination>li>a:focus,.pagination>li>a:hover,.pagination>li>span:focus,.pagination>li>span:hover{z-index:2;color:#23527c;background-color:#eee;border-color:#ddd}.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover{z-index:3;color:#fff;cursor:default;background-color:#337ab7;border-color:#337ab7}.pagination>.disabled>a,.pagination>.disabled>a:focus,.pagination>.disabled>a:hover,.pagination>.disabled>span,.pagination>.disabled>span:focus,.pagination>.disabled>span:hover{color:#777;cursor:context-menu;background-color:#fff;border-color:#ddd}.pagination-lg>li>a,.pagination-lg>li>span{padding:10px 16px;font-size:18px;line-height:1.3333333}.pagination-lg>li:first-child>a,.pagination-lg>li:first-child>span{border-top-left-radius:6px;border-bottom-left-radius:6px}.pagination-lg>li:last-child>a,.pagination-lg>li:last-child>span{border-top-right-radius:6px;border-bottom-right-radius:6px}.pagination-sm>li>a,.pagination-sm>li>span{padding:5px 10px;font-size:12px;line-height:1.5}.pagination-sm>li:first-child>a,.pagination-sm>li:first-child>span{border-top-left-radius:3px;border-bottom-left-radius:3px}.pagination-sm>li:last-child>a,.pagination-sm>li:last-child>span{border-top-right-radius:3px;border-bottom-right-radius:3px}

		table {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 98%;
			margin-right: 5%;
		}

		.pagination li:hover{
			cursor: pointer;
		}
	</style>

	<br><br>

	<div class="container" style="margin-top: 1%;">
		<div class="form-group">
			<label for="maxRows" style="color: #1c1c1c; text-align: left; font-weight: bold;">Select Number Of Rows: </label>
			<select class ="form-control" name="state" id="maxRows" disabled>
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="15">15</option>
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
		</div>

		<table class="mo_openid_reports_tables" id="reports_table" style="background-color: #ddd;cursor: context-menu;">
			<tr>
				<th>Sr. No.</th>
				<th>User ID</th>
				<th>Username</th>
				<th>Name</th>
				<th>Email</th>
				<th>Role</th>
				<th>Linked Social Profile</th>
				<th>Last Login</th>
			</tr>
			<tr>
				<td>1</td>
				<td>1</td>
				<td>Admin</td>
				<td>Admin</td>
				<td>admin@example.com</td>
				<td>administrator</td>
				<td>facebook, google</td>
				<td>01 Mar, 2021 17:53:25</td>
			</tr>
			<tr>
				<td>2</td>
				<td>2</td>
				<td>johndoe</td>
				<td>John Doe</td>
				<td>johndoe@example.com</td>
				<td>contributor</td>
				<td>google, linkedin</td>
				<td>02 Mar, 2021 13:31:13</td>
			</tr>
			<tr>
				<td>3</td>
				<td>3</td>
				<td>Jane S</td>
				<td>sjane</td>
				<td>sjane@example.com</td>
				<td>author</td>
				<td>facebook, google, linkedin</td>
				<td>01 Mar, 2021 17:21:50</td>
			</tr>
			<tr>
				<td>4</td>
				<td>4</td>
				<td>Jack H</td>
				<td>jackh</td>
				<td>jackh@example.com</td>
				<td>author</td>
				<td>facebook, google, livejournal</td>
				<td>02 Mar, 2021 15:33:45</td>
			</tr>
			<tr>
				<td>5</td>
				<td>5</td>
				<td>janedoe</td>
				<td>Jane Doe</td>
				<td>jdoe@example.com</td>
				<td>subscriber</td>
				<td>facebook, google, twitter</td>
				<td>03 Mar, 2021 12:33:15</td>
			</tr>

		</table>

		<br><br>

		<a href="#" id="xx" style="text-decoration:none;color:#000;background-color:#ddd;border:1px solid #ccc;padding:8px;cursor:context-menu;" disabled>Export this Table data into Excel</a>

		<br><br>

		<img style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/usage_share_reports.png" alt="usage share"/>
		<br><br>

	</div>

	<br><br>

	<div class="container">
		<label for="select_user" style="color: #1c1c1c; text-align: left; font-weight: bold;">SELECT USER ID: </label>
		<form action="" method="POST">
			<select id="select_user" name="selecteduid" onchange="this.form.submit();">
			<option>5</option>

			</select>
<!--            <input type="submit" value="Submit" />-->
		</form>
	</div>

	<table class="mo_openid_reports_tables" id="per_user" style="background-color: #ddd;cursor: context-menu;">
		<tr>
			<th>User ID</th>
			<th>Email</th>
			<th>Username</th>
			<th>Pages Visited</th>
			<th>No. of Times Visited</th>
		</tr>

		<tr>
			<td>5</td>
			<td>jdoe@example.com</td>
			<td>janedoe</td>
			<td>/homepage/</td>
			<td>7</td>
		</tr>

		<tr>
			<td>5</td>
			<td>jdoe@example.com</td>
			<td>janedoe</td>
			<td>/sample-page/</td>
			<td>10</td>
		</tr>

		<tr>
			<td>5</td>
			<td>jdoe@example.com</td>
			<td>janedoe</td>
			<td>/privacy-policy/</td>
			<td>3</td>
		</tr>

	</table>

	<br><br>

	<img style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/most_visited_reports.png" alt="usage share"/>

	<br><br>

</div>


<!--  =================================================================================================================================================================================================  -->

	<?php
}
