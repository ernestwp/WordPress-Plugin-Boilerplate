const {__} = wp.i18n;

const {
    assign
} = lodash;

const {
    addFilter
} = wp.hooks;

const {
    SelectControl,
    PanelBody,
    TextControl
} = wp.components;

const {
    Fragment
} = wp.element;

const {
    createHigherOrderComponent
} = wp.compose;

const {
    InspectorControls
} = wp.editor;

export const addPostListSettings = createHigherOrderComponent((BlockEdit) => {
    return (props) => {
        // Check if we have to do something
        if (props.name == 'plugin-slug/post-list' && props.isSelected) {
            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>

                        <PanelBody title={__('List Posts Settings', 'text-domain')}>

                            <TextControl
                                label={ __( 'Class', 'text-domain' ) }
                                value={ props.attributes.listClass }
                                type="string"
                                onChange={ ( value ) => {
                                    props.setAttributes({
										listClass: value
                                    });
                                }}
                            />

                            <SelectControl
                                label={__('List Type', '')}
                                value={props.attributes.listType}
                                options={ [
									{ label: 'Bullets', value: 'bullets' },
									{ label: 'Numbered', value: 'numbered' },
                                ] }
                                onChange={(value) => {
                                    props.setAttributes({listType: value});
                                }}
                            />

                        </PanelBody>

                    </InspectorControls>
                </Fragment>
            );
        }

        return <BlockEdit {...props} />;
    };
}, 'addPostListSettings');

addFilter('editor.BlockEdit', 'plugin-slug/post-list', addPostListSettings);
