<?php
/**
 * WP List Table Example class
 *
 * @package   CustomTailorShop
 * @author    Dylan Bui
 */

class List_Table extends WP_List_Table {
	public function __construct() {
		// Set parent defaults.
		parent::__construct( array(
			'singular' => 'invoice',     // Singular name of the listed records.
			'plural'   => 'invoices',    // Plural name of the listed records.
			'ajax'     => false,       // Does this table support ajax?
		) );
	}

	public function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />', // Render a checkbox instead of text.
			'name'    => _x( 'Name', 'Column label', 'wp-list-table' ),
			'email'   => _x( 'Email', 'Column label', 'wp-list-table' ),
			'phone_number' => _x( 'Phone Number', 'Column label', 'wp-list-table' ),
		);

		return $columns;
	}

	protected function get_sortable_columns() {
		$sortable_columns = array(
			'name'    => array( 'name', false ),
			'email'   => array( 'email', false ),
			'phone_number' => array( 'phone_number', false ),
		);

		return $sortable_columns;
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'email':
			case 'phone_number':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
		}
	}

	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],  
			$item['invoice']               
		);
	}

	protected function column_name( $item ) {
		$page = wp_unslash( $_REQUEST['page'] ); // WPCS: Input var ok.


		// Build delete row action.
		$delete_query_args = array(
			'page'   => $page,
			'action' => 'delete',
			'invoice'  => $item['invoice'],
		);

		$actions['delete'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( wp_nonce_url( add_query_arg( $delete_query_args, 'admin.php' ), 'delete' . $item['name'] ) ),
			_x( 'Delete', 'List table row action', 'wp-list-table' )
		);

		// Return the title contents.
		return sprintf( '%1$s <span style="color:silver;">(invoice:%2$s)</span>%3$s',
			$item['name'],
			$item['invoice'],
			$this->row_actions( $actions )
		);
	}


	protected function get_bulk_actions() {
		$actions = array(
			'delete' => _x( 'Delete', 'List table bulk action', 'wp-list-table' ),
		);

		return $actions;
	}
	
	protected function process_bulk_action() {
		global $wpdb; 
		// Detect when a bulk action is being triggered.
		if ( 'delete' === $this->current_action() ) {
			$ids=$_GET['invoice'];
			if(is_array($ids)){
					foreach($ids as $id){
						$wpdb->query("DELETE FROM wp_tailor_shop_data WHERE invoice_number = $id");
					}
			}
			else{
				$wpdb->query("DELETE FROM wp_tailor_shop_data WHERE invoice_number = $ids");
			}
			wp_die( "Request:". json_encode($ids) ." deleted" );
		}
	}

	function prepare_items() {
		global $wpdb; //This is used only if making any database queries

		$per_page = 5;

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		//Get Data
		$database_name="wp_tailor_shop_data";
		$data = array();
		$ids = $wpdb->get_col( "SELECT DISTINCT invoice_number FROM wp_tailor_shop_data" );
		foreach($ids as $value){
			//0=name,1=email,2=phone
			$individual_data= $wpdb->get_col( "SELECT meta_value FROM wp_tailor_shop_data WHERE invoice_number=$value" );
			$temp=array(
				'invoice' =>$value,
				'name'    => $individual_data[0],
				'email'   => $individual_data[1],
				'phone_number' => $individual_data[2],
			);
			array_push($data,$temp);
		};
	
		usort( $data, array( $this, 'usort_reorder' ) );

		$current_page = $this->get_pagenum();

		$total_items = count( $data );

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->items = $data;

		$this->set_pagination_args( array(
			'total_items' => $total_items,                     // WE have to calculate the total number of items.
			'per_page'    => $per_page,                        // WE have to determine how many items to show on a page.
			'total_pages' => ceil( $total_items / $per_page ), // WE have to calculate the total number of pages.
		) );
	}

	protected function usort_reorder( $a, $b ) {
		// If no sort, default to title.
		$orderby = ! empty( $_REQUEST['orderby'] ) ? wp_unslash( $_REQUEST['orderby'] ) : 'title'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = ! empty( $_REQUEST['order'] ) ? wp_unslash( $_REQUEST['order'] ) : 'asc'; // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		return ( 'asc' === $order ) ? $result : - $result;
	}
}
