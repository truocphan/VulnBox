import { updateCategory } from '@wordpress/blocks';
import React from 'react';

export function updateBlocksCategoryIcon() {
	updateCategory('masteriyo', {
		icon: (
			<svg
				version="1.1"
				id="mto-logo"
				xmlns="http://www.w3.org/2000/svg"
				xmlnsXlink="http://www.w3.org/1999/xlink"
				x="0px"
				y="0px"
				viewBox="0 0 24 24"
				enableBackground="new 0 0 24 24"
				xmlSpace="preserve"
			>
				<style type="text/css">
					{`.st0{opacity:0.3;fill:#787DFF;enable-background:new    ;}
				.st1{opacity:0.5;fill:#787DFF;enable-background:new    ;}
				.st2{fill:#787DFF;}
				.st3{opacity:0.3;fill:#FD739C;enable-background:new    ;}
				.st4{opacity:0.5;fill:#FD739C;enable-background:new    ;}
				.st5{fill:#FD739C;}`}
				</style>
				<g>
					<path
						className="st0"
						d="M5.9,3.5l4,4.5c0.6,0.7,1,1.6,1,2.5V21l-5-3.5L5.9,3.5z"
					/>
					<path
						className="st1"
						d="M2.8,2.9l6.6,4.7c0.9,0.6,1.4,1.6,1.5,2.7c0,0.1,0,0.1,0,0.2V21l-8.2-4.3L2.8,2.9z"
					/>
					<path
						className="st2"
						d="M10.9,21.1l-7.3-2.4C2.1,18.3,1.1,17,1,15.5c0-0.1,0-0.1,0-0.2V4.6l8,3.1c1.1,0.4,1.8,1.4,1.9,2.6
		c0,0.1,0,0.1,0,0.2L10.9,21.1z"
					/>
					<path
						className="st3"
						d="M18.1,3.5l-4,4.5c-0.6,0.7-1,1.6-1,2.5V21l5-3.5L18.1,3.5z"
					/>
					<path
						className="st4"
						d="M21.2,2.9l-6.6,4.7c-0.9,0.6-1.4,1.6-1.5,2.7c0,0.1,0,0.1,0,0.2V21l8.2-4.3L21.2,2.9z"
					/>
					<path
						className="st5"
						d="M13.1,21.1l7.3-2.4c1.4-0.5,2.5-1.7,2.6-3.3c0-0.1,0-0.1,0-0.2V4.6l-8,3.1c-1.1,0.4-1.8,1.4-1.9,2.6
		c0,0.1,0,0.1,0,0.2L13.1,21.1z"
					/>
				</g>
			</svg>
		),
	});
}
