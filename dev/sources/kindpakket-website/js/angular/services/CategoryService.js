kindpakketApp.service('CategoryService', ['$http', '$q', 'ApiRequest', function($http, $q, ApiRequest) {
    var categories = [{
        id: 1,
        name: 'Personal care',
        preview: 'http://localhost:3000/assets/img/personal.jpg',
        original: '',
        shopkeepers: [{
            id: 1,
            name: 'ShopKeeper #1',
            phone: '+843537264578324',
            offices: [{
                id: 1,
                address: 'Cahul, Stefan cel Mare 28',
                lon: '28.1995602',
                lat: '45.90843940000001',
                preview: 'http://localhost:3000/assets/img/personal.jpg'
            }, {
                id: 2,
                address: 'Cahul, Stefan cel Mare 53',
                lon: '28.4995602',
                lat: '45.50843940000001',
                preview: 'http://localhost:3000/assets/img/personal.jpg'
            }]
        }, {
            id: 2,
            name: 'ShopKeeper #2',
            phone: '+943509348534343',
            offices: [{
                id: 3,
                address: 'Chisinau, Stefan cel Mare 28',
                lon: '29.1995602',
                lat: '47.90843940000001',
                preview: 'http://localhost:3000/assets/img/personal.jpg'
            }, {
                id: 4,
                address: 'Chisinau, Stefan Cel Mare 31',
                lon: '26.4995602',
                lat: '41.50843940000001',
                preview: 'http://localhost:3000/assets/img/personal.jpg'
            }]
        }]
    }, {
        id: 2,
        name: 'Home care',
        preview: 'http://localhost:3000/assets/img/home.jpg',
        original: '',
        shopkeepers: [{
            id: 3,
            name: 'ShopKeeper #3',
            phone: '+843537264578324',
            offices: [{
                id: 5,
                address: 'Cahul, Stefan cel Mare 28',
                lon: '26.1995602',
                lat: '43.90843940000001',
                preview: 'http://localhost:3000/assets/img/home.jpg'
            }, {
                id: 6,
                address: 'Cahul, Stefan cel Mare 53',
                lon: '21.4995602',
                lat: '46.50843940000001',
                preview: 'http://localhost:3000/assets/img/home.jpg'
            }]
        }, {
            id: 4,
            name: 'ShopKeeper #4',
            phone: '+943509348534343',
            offices: [{
                id: 7,
                address: 'Chisinau, Stefan cel Mare 28',
                lon: '23.1995602',
                lat: '47.90843940000001',
                preview: 'http://localhost:3000/assets/img/home.jpg'
            }, {
                id: 8,
                address: 'Chisinau, Stefan Cel Mare 31',
                lon: '21.4995602',
                lat: '47.50843940000001',
                preview: 'http://localhost:3000/assets/img/home.jpg'
            }]
        }]
    }];

    var getCategories = function() {
        return ApiRequest.get('/api/categories');
        return $q(function(resolve, reject) {
            resolve(categories);
        });
    };

    return {
        getCategories: getCategories
    };
}]);