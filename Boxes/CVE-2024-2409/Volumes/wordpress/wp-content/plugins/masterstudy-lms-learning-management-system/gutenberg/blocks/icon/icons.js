import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import {
  PanelRow,
  SearchControl,
  __experimentalScrollable as Scrollable,
} from "@wordpress/components";
import { Fragment, useState, useEffect } from "@wordpress/element";
import { useDebounce } from "../../common/hooks";

const FontAwesomeIconList = ({ selectedIcon, setAttributes }) => {
  const [icons] = useState(() => Object.assign({}, fas, fab, far));
  const [iconNames] = useState(() => Object.keys(icons));
  const [iconNamesSort, setIconNamesSort] = useState([]);
  const [search, setSearch] = useState("");
  const debounceSearch = useDebounce(search, 800);
  useEffect(() => {
    const updateIconsList = () => {
      if (iconNames.length) {
        if (debounceSearch.trim() === "") {
          setIconNamesSort(iconNames);
        } else {
          const filterIconNames = iconNames.filter((value) =>
            value.toLowerCase().includes(debounceSearch.toLowerCase()),
          );
          setIconNamesSort(filterIconNames);
        }
      }
    };
    updateIconsList();
  }, [iconNames, debounceSearch]);

  return (
    <Fragment>
      <PanelRow>
        <SearchControl value={search} onChange={setSearch} />
      </PanelRow>
      <PanelRow>
        <Scrollable style={{ maxHeight: 200, minHeight: 80 }}>
          <div className="masterstudy-icon__list">
            {iconNamesSort.map((togglerIconName) => (
              <div
                className={`masterstudy-icon__list-item ${
                  selectedIcon === togglerIconName ? "selected" : ""
                }`}
                onClick={() =>
                  setAttributes(togglerIconName, icons[togglerIconName])
                }
              >
                <FontAwesomeIcon icon={icons[togglerIconName]} />
              </div>
            ))}
          </div>
        </Scrollable>
      </PanelRow>
    </Fragment>
  );
};

export default FontAwesomeIconList;
