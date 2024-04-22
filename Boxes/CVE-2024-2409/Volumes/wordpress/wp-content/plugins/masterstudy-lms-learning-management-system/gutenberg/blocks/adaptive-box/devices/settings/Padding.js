import { __ } from "@wordpress/i18n";
import {
  PanelRow,
  __experimentalBoxControl as BoxControl,
} from "@wordpress/components";

const Padding = ({ values, onChangeCssProperty }) => {
  return (
    <PanelRow>
      <BoxControl
        values={values.padding || undefined}
        label={__("Padding", "masterstudy-lms-learning-management-system")}
        onChange={(value) =>
          onChangeCssProperty({
            type: "object",
            cssProperty: "padding",
            value,
          })
        }
      />
    </PanelRow>
  );
};
export default Padding;
