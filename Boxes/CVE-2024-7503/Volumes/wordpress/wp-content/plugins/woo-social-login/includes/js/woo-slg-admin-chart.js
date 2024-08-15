"use strict";

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback( WooSlgDrawChart );

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
// function WooSlgDrawChart() {

//     var data_row = WOOSlgChart.datarows;

//     // Create the data table.
//     var deals_social_data = new google.visualization.DataTable();
//     deals_social_data.addColumn('string', 'Topping');
//     deals_social_data.addColumn('number', 'Slices');
//     deals_social_data.addRows(data_row);
    
//     // Set chart options
//     var deals_social_chart_options = {
//     				'title':WOOSlgChart.title,
//                    	'width':'auto',
//                    	'height':450
//     			}; 

//     // Instantiate and draw our chart, passing in some options.
//     var deals_social_chart = new google.visualization.PieChart(document.getElementById('woo_slg_social_chart_element'));
//     deals_social_chart.draw(deals_social_data, deals_social_chart_options );
// }

function WooSlgDrawChart() {

    var data_row = WOOSlgChart.datarows;

    // Create the data table.
    var deals_social_data = new google.visualization.DataTable();
    deals_social_data.addColumn('string', 'Topping');
    deals_social_data.addColumn('number', 'Slices');
    deals_social_data.addRows(data_row);
    if (jQuery(window).width() >= 821) {
        // Set chart options
        var deals_social_chart_options = {
            'title': WOOSlgChart.title,
            'width': 700,
            'height': 550,
            'chartArea': {
                'width': '100%',
                'height': '100%'
            },       
            'fontName': 'Inter', 
            'fontSize': 16, 
            'fontWeight': 700, 
            'lineHeight': 21.35, 
            'legend': {
                'textStyle': {
                    'fontName': 'Inter',
                    'fontSize': 16, 
                    'fontWeight': 700, 
                    'lineHeight': 21.35,
                    'alignitem': 'center',
                }
            },
            'titleTextStyle': {
                'fontName': 'Inter',
                'fontSize': 18, 
                'fontWeight': 700, 
                'lineHeight': 21.35,
                'alignitem': 'center',
            }
        };
    }else  {
        var deals_social_chart_options = {
            'title':WOOSlgChart.title,
            'width':450,
            'height':350,
            'chartArea': {
                'width': '90%',
                'height': '80%'
            }, 
        }; 
    }

    // Instantiate and draw our chart, passing in some options.
    var deals_social_chart = new google.visualization.PieChart(document.getElementById('woo_slg_social_chart_element'));
    deals_social_chart.draw(deals_social_data, deals_social_chart_options );
}