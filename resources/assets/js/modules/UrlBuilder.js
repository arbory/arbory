export default class UrlBuilder
{
    constructor(params)
    {
        if (params === undefined) {
            params = {}
        }
        var keepCurrentQuery = true
        if (params === false || params.baseUrl !== undefined) {
            keepCurrentQuery = false
        }
        if (typeof params === 'string') {
            params = { baseUrl: params }
        }
        // setup members
        this.path = ''
        this.query = {}
        // get url
        var baseUrl = params.baseUrl || location.href
        // remove anchor
        baseUrl = baseUrl.split('#').shift()
        // split url
        var urlParts = baseUrl.split('?')
        this.path = urlParts.shift()
        if (keepCurrentQuery && urlParts.length > 0) {
            var queryParts = urlParts.shift().split('&')
            for (var i = 0; i < queryParts.length; i++) {
                if (queryParts[i].length > 0) {
                    var value
                    var variable = queryParts[i].split('=')
                    var name = variable.shift()

                    if (variable.length > 0) {
                        value = decodeURIComponent(variable.shift())
                    } else {
                        value = ''
                    }

                    if (decodeURIComponent(name).substr(decodeURIComponent(name).length - 2, 2) === '[]') {
                        name = decodeURIComponent(name)
                    }

                    if (name.substr(name.length - 2, 2) === '[]') {
                        name = name.substr(0, name.length - 2)
                        if (this.query[name] === undefined || !(this.query[name] instanceof Array)) {
                            this.query[name] = []
                        }
                        this.query[name].push(value)
                    } else {
                        this.query[name] = value
                    }
                }
            }
        }
        if (params.keep !== undefined && params.keep instanceof Array) {
            var filteredQuery = {}
            for (var a = 0; a < params.keep.length; a++) {
                if (this.query[params.keep[a]] !== undefined) {
                    filteredQuery[params.keep[a]] = this.query[params.keep[a]]
                }
            }
            this.query = filteredQuery
        }
    }

    add(params, value)
    {
        if (params instanceof Array) {
            for (var i = 0; i < params.length; i++) {
                if (params[i].name !== undefined && params[i].value !== undefined) {
                    var name = params[i].name
                    if (name.substr(name.length - 2, 2) === '[]') {
                        name = name.substr(0, name.length - 2)
                        if (this.query[name] === undefined || !(this.query[name] instanceof Array)) {
                            this.query[name] = []
                        }
                        this.query[name].push(params[i].value)
                    } else {
                        this.query[params[i].name] = params[i].value
                    }
                }
            }
        } else {
            if (params instanceof Object) {
                for (var a in params) {
                    if (params.hasOwnProperty(a)) {
                        this.query[a] = params[a]
                    }
                }
            } else {
                if (typeof params === 'string') {
                    if (value === undefined) {
                        var temp = new UrlBuilder('?' + params)
                        for (var b in temp.query) {
                            if (temp.query.hasOwnProperty(b)) {
                                this.query[b] = temp.query[b]
                            }
                        }
                    } else {
                        this.query[params] = value
                    }
                }
            }
        }
        return this
    }

    removeAll(preserveParams)
    {
        for (var i in this.query) {
            if (preserveParams === undefined || jQuery.inArray(i, preserveParams) === -1) {
                this.remove(i)
            }
        }
        return this
    }

    remove(name)
    {
        delete this.query[name]
        return this
    }

    get(name)
    {
        if (this.query[name] !== undefined) {
            return this.query[name]
        }
        return null
    }

    getUrl()
    {
        var query = ''
        var isFirst = true
        for (var i in this.query) {
            if (this.query.hasOwnProperty(i)) {
                if (!isFirst) {
                    query += '&'
                } else {
                    isFirst = false
                }
                if (this.query[i] instanceof Array) {
                    query += i + '[]=' + this.query[i].map(encodeURIComponent).join('&' + i + '[]=')
                } else {
                    query += i + '=' + encodeURIComponent(this.query[i])
                }
            }
        }

        return this.path + '?' + query
    }
}