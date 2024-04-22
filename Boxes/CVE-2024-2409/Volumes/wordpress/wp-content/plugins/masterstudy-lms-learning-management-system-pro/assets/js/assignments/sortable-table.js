(function($){
    $(document).ready(function() {
        // Start: Table column sorting
        let sortIndex  = 0;
        let sorters    = {};

        $.each( $('.masterstudy-tcell__header'), function( i, header ) {
            // Sorting data on table header click 
            $( header ).parent().on ( 'click', function() {
                const sortby        = $( header ).data('sort');
                const indicatorUp   = $( header ).find( '.masterstudy-thead__sort-indicator__up' );
                const indicatorDown = $( header ).find( '.masterstudy-thead__sort-indicator__down' );
                const sortOrders    = [ 'asc', 'desc', 'none' ];
                // Sorting indexes
                if ( sorters[sortby] === undefined || sorters[sortby] === null ) {
                    sorters[sortby] = 0;
                } 
                sorters[sortby] = sorters[sortby] < 3 ? sorters[sortby]: 0;
                sortIndex       = sorters[sortby];
                // Reset indicator states
                $( '.masterstudy-thead__sort-indicator_is-hidden' ).removeClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                // Asc sort indicator show
                switch (sortOrders[sortIndex]) {
                    case 'asc':
                        indicatorDown.addClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        indicatorUp.removeClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        break;
                    case 'desc':
                        indicatorDown.removeClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        indicatorUp.addClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        break;
                    case 'none':
                        // Fall through intentionally
                    default:
                        indicatorDown.removeClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        indicatorUp.removeClass( 'masterstudy-thead__sort-indicator_is-hidden' );
                        break;
                }
                // Sorts items
                sortTableItems( sortOrders[sortIndex], sortby );

                sorters[sortby]++;
            });

        });

        function sortTableItems( sortOrder, sortby ) {
            const itemContainer = $( '.masterstudy-tbody' );
            const sortingItems  = itemContainer.find( '.masterstudy-table__item' );

            if ( sortOrder === undefined || sortby === undefined ) {
                return;
            }
            
            sortingItems.sort( function( a, b ) {
                let aValue = $(a).find('.masterstudy-tcell [data-sort="' + sortby + '"]').text().trim();
                let bValue = $(b).find('.masterstudy-tcell [data-sort="' + sortby + '"]').text().trim();

                 // Handle empty values
                if (aValue === '' && bValue === '') {
                    return 0;
                }

                if (aValue === '' || bValue === '') {
                    return sortOrder === 'asc' ? (aValue === '' ? -1 : 1) : (aValue === '' ? 1 : -1);
                }

                let sorted = 0;

                if ( sortOrder === 'none' ) {
                    sorted =  $(a).data( 'initial-order' ) - $(b).data( 'initial-order' );
                } else {
                    const isDate   = !isNaN(Date.parse(aValue)) && !isNaN(Date.parse(bValue));
                    const isNumber = !isNaN(parseFloat(aValue)) && isFinite(aValue);

                    if (!isDate && !isNumber) {
                        aValue = aValue.toLowerCase();
                        bValue = bValue.toLowerCase();
                        sorted = ( sortOrder === 'asc' ) ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                    } else if (isDate) {
                        aValue = new Date(aValue);
                        bValue = new Date(bValue);
                        sorted = ( sortOrder === 'asc' ) ?  aValue - bValue : bValue - aValue;
                    } else if (isNumber) {
                        aValue = parseFloat(aValue);
                        bValue = parseFloat(bValue);
                        sorted = ( sortOrder === 'asc' ) ?  aValue - bValue : bValue - aValue;
                    }
                }

                return sorted;
            });

            itemContainer.empty().append( sortingItems );
        }

        // Store the original order of elements
        $( '.masterstudy-tbody .masterstudy-table__item' ).each( function( index, item ) {
            $( item ).attr( 'data-initial-order', index );
        });
        // End: Table column sorting

        $('.masterstudy-table__filters-icon').on( 'click', function() {
            $('.masterstudy-table__filters-dropdown').toggleClass( 'masterstudy-table__filters-dropdown_open')
        });

        $('.masterstudy-table__filters-dropdown__close').on( 'click', function() {
            $('.masterstudy-table__filters-dropdown').removeClass( 'masterstudy-table__filters-dropdown_open')
        });
    });
})(jQuery);