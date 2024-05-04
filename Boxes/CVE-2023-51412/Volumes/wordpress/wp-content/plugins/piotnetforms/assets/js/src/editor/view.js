export default class View {
    constructor() {
        this.groups = {};
    }

    add_attribute( group_name, key, value, overwrite ) {
        let group = this.groups[group_name];
        if (!group) {
            group = {};
            this.groups[group_name] = group;
        }

        if (!group[key]) {
            group[key] = [];
        }

        if ( ! Array.isArray( value ) ) {
            value = [ value ];
        }

        if ( overwrite ) {
            group[key] = value;
        } else {
            group[key] = group[key].concat( value );
        }
    }

    add_multi_attribute(groups) {
        const group_names = Object.keys(groups);
        for (const group_name of group_names) {
            const group = groups[group_name];
            const keys = Object.keys(group);
            for (const key of keys) {
                const value = group[key];
                this.add_attribute( group_name, key, value )
            }
        }
    }

    remove_group_attribute( group_name ) {
        delete this.groups[group_name]; 
    }

    remove_attribute( group_name, key ) {
        delete this.groups[group_name][key]; 
    }

    render_attributes ( group_name ) {
        const group = this.groups[group_name];
        if (!group) {
            return '';
        }

        const attributes = [];

        jQuery.each( group, ( key, value ) => {
            attributes.push( key + '="' + _.escape( value.join( ' ' ) ) + '"' );
        } );

        return attributes.join( ' ' );
    }

}
