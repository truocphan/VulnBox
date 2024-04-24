import { useInstanceId } from '@wordpress/compose';
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import React from 'react';
import useClickOutside from '../../hooks/useClickOutside';
import { useDeviceType } from '../../hooks/useDeviceType';
import DeviceSelector from '../device-selector';
import Icon from '../icon';
import './editor.scss';

interface PropsType {
	value: any;
	// eslint-disable-next-line no-unused-vars
	onChange: (value: any) => void;
	label: string;
	options: {
		label: string;
		value: any;
	}[];
	inline: boolean;
	placeholder: string;
	responsive: boolean;
	search: boolean;
}

const Select: React.FC<PropsType> = (props) => {
	const [isOpen, setIsOpen] = useState(false);
	const [focus, setFocus] = useState(0);
	const [searchTerm, setSearchTerm] = useState('');
	const {
		value,
		onChange,
		label,
		options,
		inline = true,
		placeholder = __('Select', 'masteriyo'),
		responsive = false,
		search = false,
	} = props;
	const selectRef = useRef<any>();
	const inputRef = useRef<any>();
	const id = useInstanceId(Select);
	const devices = {
		desktop: 'Desktop',
		tablet: 'Tablet',
		mobile: 'Mobile',
	};
	const [deviceType] = useDeviceType();

	useEffect(() => {
		const ref = inputRef.current;

		if (isOpen && ref) {
			ref.focus();
		}

		return () => {
			if (ref) {
				ref.blur();
			}
		};
	}, [isOpen]);

	useClickOutside(selectRef, () => setIsOpen(false));

	const getValue = () => {
		if (responsive) {
			return value && value[deviceType]
				? options.filter((option) => option.value === value[deviceType])[0]
						.label
				: placeholder;
		}

		// eslint-disable-next-line no-nested-ternary
		return value
			? options.some((option) => value === option.value)
				? options.filter((option) => option.value === value)[0].label
				: options[0].label
			: placeholder;
	};

	const setSettings = (type: any, val: any) => {
		const data = { [type]: val };
		onChange(Object.assign({}, value, data));
		setIsOpen(false);
		setSearchTerm('');
	};

	const finalOptions = () => {
		const selected =
			options.filter((option) => getValue() === option.label) || [];

		if (selected.length > 0) {
			options.forEach((option, index) => {
				if (option.value === selected[0].value) {
					options.splice(index, 1);
					options.unshift(option);
				}
			});
		}

		if (search) {
			const temp = options
				.filter(
					(option) => getValue().toLowerCase() !== option.label.toLowerCase()
				)
				.filter((option) => option.label.toLowerCase().includes(searchTerm));

			if (selected.length > 0) {
				temp.unshift(selected[0]);
			}

			return temp;
		}

		return options;
	};

	const onKeydownHandler = (e: any, device: string | undefined = undefined) => {
		switch (e.keyCode) {
			case 13:
				if (device) {
					setSettings(device, finalOptions()[focus].value);
				} else {
					onChange(finalOptions()[focus].value);
				}

				setIsOpen(false);

				break;
			case 38:
				if (focus === 0) {
					setFocus(finalOptions().length - 1);
					return;
				}

				setFocus(focus - 1);
				break;
			case 40:
				if (focus === finalOptions().length - 1) {
					setFocus(0);

					return;
				}

				setFocus(focus + 1);

				break;
			case 27:
				setIsOpen(false);
		}
	};

	return (
		<div
			className={classnames(
				'masteriyo-control',
				'masteriyo-select',
				{ 'masteriyo-responsive': responsive },
				{ 'masteriyo-inline': inline && !responsive }
			)}
		>
			<div className="masteriyo-control-head masteriyo-select-head">
				{label && (
					<label
						htmlFor={`masteriyo-select-button-${id}`}
						className="masteriyo-control-label masteriyo-select-label"
					>
						{label}
					</label>
				)}
				{responsive && <DeviceSelector />}
			</div>
			<div
				className="masteriyo-control-body masteriyo-select-body"
				ref={selectRef}
			>
				{responsive ? (
					Object.keys(devices).map(
						(deviceKey) =>
							deviceKey === deviceType && (
								<Fragment key={deviceKey}>
									<button
										id={`masteriyo-select-button-${id}`}
										className="masteriyo-select-button"
										onClick={() => setIsOpen(!isOpen)}
										aria-expanded={isOpen}
										aria-haspopup="listbox"
										onKeyDown={(e) => onKeydownHandler(e, deviceKey)}
									>
										{getValue()}
										<Icon
											type="controlIcon"
											name={isOpen ? 'chevron-up' : 'chevron-down'}
											size={12}
										/>
									</button>
									<ul
										className="masteriyo-select-menu"
										role="listbox"
										aria-hidden={!isOpen}
										tabIndex={-1}
									>
										{isOpen && (
											<Fragment>
												{search && (
													<li
														className="masteriyo-select-menu-item has-search"
														role="option"
														tabIndex={-1}
													>
														<input
															ref={inputRef}
															placeholder={__('Search', 'masteriyo')}
															type="text"
															value={searchTerm}
															onKeyDown={(e) => onKeydownHandler(e, deviceKey)}
															onChange={(e) => setSearchTerm(e.target.value)}
														/>
													</li>
												)}
												{finalOptions().map((option, idx) => (
													<li
														key={option.value}
														value={option.value}
														className={classnames(
															'masteriyo-select-menu-item',
															{ 'is-active': option.label === getValue() },
															{ 'is-focus': idx === focus }
														)}
														onClick={() => setSettings(deviceKey, option.value)}
														role="option"
														onMouseOver={() => setFocus(idx)}
														onFocus={() => setFocus(idx)}
														aria-selected={idx === focus}
														tabIndex={-1}
														onKeyDown={() => null}
													>
														{option.label}
													</li>
												))}
											</Fragment>
										)}
									</ul>
								</Fragment>
							)
					)
				) : (
					<Fragment>
						<button
							id={`masteriyo-select-button-${id}`}
							className="masteriyo-select-button"
							onClick={() => setIsOpen(!isOpen)}
							aria-expanded={isOpen}
							aria-haspopup="listbox"
							onKeyDown={(e) => onKeydownHandler(e)}
						>
							{getValue()}
							<Icon
								type="controlIcon"
								name={isOpen ? 'chevron-up' : 'chevron-down'}
								size={12}
							/>
						</button>
						<ul className="masteriyo-select-menu" aria-hidden={!isOpen}>
							{isOpen && (
								<Fragment>
									{search && (
										<li
											className="masteriyo-select-menu-item has-search"
											role="option"
											tabIndex={-1}
										>
											<input
												ref={inputRef}
												placeholder={__('Search', 'masteriyo')}
												type="text"
												value={searchTerm}
												onKeyDown={(e) => onKeydownHandler(e)}
												onChange={(e) => setSearchTerm(e.target.value)}
											/>
										</li>
									)}
									{finalOptions().map((option, idx) => (
										<li
											key={option.value}
											value={option.value}
											className={classnames(
												'masteriyo-select-menu-item',
												{ 'is-active': option.label === getValue() },
												{ 'is-focus': idx === focus }
											)}
											onClick={() => {
												onChange(option.value);
												setIsOpen(false);
											}}
											role="option"
											onKeyDown={() => null}
											onMouseOver={() => setFocus(idx)}
											onFocus={() => setFocus(idx)}
											aria-selected={idx === focus}
											tabIndex={-1}
										>
											{option.label}
										</li>
									))}
								</Fragment>
							)}
						</ul>
					</Fragment>
				)}
			</div>
		</div>
	);
};

export default Select;
