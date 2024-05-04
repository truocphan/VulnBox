export default class ObjectUtil {
    static clone(obj) {
        return JSON.parse(JSON.stringify(obj));
    }
}
