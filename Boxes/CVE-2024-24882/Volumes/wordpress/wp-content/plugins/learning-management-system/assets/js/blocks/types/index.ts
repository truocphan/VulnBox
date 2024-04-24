export interface StringToAnyObject {
	[key: string]: any;
}

export interface WPBlock {
	attributes: StringToAnyObject;
	clientId: string;
	innerBlocks: WPBlock[];
	isValid: boolean;
	name: string;
	originalContent: string;
	validationIssues: any[];
}

export type BlockSettingStyle = {
	selector: string;
	condition?: {
		key: string;
		relation: '==' | '===' | '!=' | '!==';
		value: string | number | boolean | any[];
	}[];
}[];

export type BlockAttributeValue =
	| string
	| number
	| boolean
	| any[]
	| {
			desktop?: any;
			tablet?: any;
			mobile?: any;

			border?: 1;
			color?: string;
			radius?: {
				desktop: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
				tablet?: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
				mobile?: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
			};
			size?: {
				desktop: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
				tablet?: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
				mobile?: {
					top: number;
					right: number;
					bottom: number;
					left: number;
					unit: 'px' | 'em' | '%';
				};
			};

			[key: string]: any;
	  };

export interface BlockAttributeDefinition {
	type: any;
	default?: BlockAttributeValue;
	style?: BlockSettingStyle;
}

export interface BlockAttributesDefinition {
	[name: string]: BlockAttributeDefinition;
}

export type BlockAttributesData = {
	[settingName: string]: BlockAttributeValue;
};
