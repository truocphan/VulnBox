export default class StringUtil {
    static replaceAll(str, find, replace) {
        return str.replace(new RegExp(find, 'g'), replace);
    }
    static isEmpty(str) {
        return !str || 0 === str.length;
    }
    static replaceAllBackSlash(str){
		var index = str.indexOf("\\");
		while( index >= 0 ){
			str = str.replace("\\","");
			index = str.indexOf("\\");
		}
		return str;
	}
}
