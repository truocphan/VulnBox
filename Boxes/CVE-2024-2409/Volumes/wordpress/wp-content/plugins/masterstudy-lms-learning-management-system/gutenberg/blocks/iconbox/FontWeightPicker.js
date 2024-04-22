import { __ } from "@wordpress/i18n";
import { ComboboxControl } from "@wordpress/components";

const options = [
  { label: "100", value: 100 },
  { label: "200", value: 200 },
  { label: "300", value: 300 },
  { label: "400", value: 400 },
  { label: "500", value: 500 },
  { label: "600", value: 600 },
  { label: "700", value: 700 },
  { label: "800", value: 800 },
  { label: "900", value: 900 },
];

export const FontWeightPicker = ({ value, onChange }) => (
  <ComboboxControl
    label={__(
      "Select font weight",
      "masterstudy-lms-learning-management-system",
    )}
    onChange={onChange}
    options={options}
    value={value}
  />
);
