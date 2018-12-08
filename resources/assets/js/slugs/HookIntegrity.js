/**
 * Class to perform hook integrity check on the target shopify store
 * and see if the hook is in place, also used to re inject the hook if not fount.
 * @author Bawa
 */
class HookIntegrity {
    constructor() {
        // ----- vacant for now :)
    }

    check(callback) {
        axios.get('/check-integrity.json').then(function(response){
            callback(response.data);
        });
    }

    fix(callback) {
        axios.get('/fix-integrity.json').then(function(response){
            callback(response.data);
        });
    }
}

export default HookIntegrity;