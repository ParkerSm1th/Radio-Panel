var urlRoute = {
    baseUrl: "",
    apiBase: "",
    folderUrl: "",
    currentCode: "Staff.Dashboard",
    errorCount: 0,

    setApiBase: function (base) {
        this.apiBase = base.replace(/\/$/, "");
        return this;
    },

    setFolderUrl: function (path) {
        this.folderUrl = path;
        return this;
    },

    setBaseUrl: function (url) {
        this.baseUrl = url.replace(/\/?$/, "/");
        return this;
    },

    setPreviousCode: function (code) {
        this.currentCode = code;
        return this;
    },

    getBaseUrl: function () {
        return this.baseUrl;
    },

    cookiePath: function () {
        var base = (this.folderUrl || "").replace(/\/app\/?$/, "");
        if (!base || base === "/") {
            return "/";
        }
        return base.charAt(0) === "/" ? base : "/" + base;
    },

    parseRouteFromUrl: function () {
        var path = window.location.pathname || "";
        var appPath = (this.folderUrl || "").replace(/\/$/, "");

        if (appPath && path.indexOf(appPath) === 0) {
            path = path.slice(appPath.length);
        }

        path = path.replace(/^\//, "").split("?")[0];

        if (path && /^[A-Za-z]+\.[A-Za-z0-9_]+$/.test(path)) {
            return path;
        }

        return "Staff.Dashboard";
    },

    checkCurrent: function () {
        this.loadPage(this.parseRouteFromUrl());
    },

    normalizeResponse: function (value) {
        return String(value == null ? "" : value).replace(/^\uFEFF/, "").trim();
    },

    loadPage: function (route) {
        if (!route) {
            route = "Staff.Dashboard";
        }

        if (typeof route !== "string") {
            route = String(route);
        }

        route = route.split("?")[0];
        if (route.charAt(0) !== "/") {
            route = "/" + route;
        }

        var code = route.substring(1).split("?")[0];
        var parts = code.split(".", 2);
        if (parts.length < 2) {
            this.pageError(code);
            return;
        }

        var area = parts[0];
        var page = parts[1];
        var query = window.location.search ? window.location.search.substring(1) : "";

        if (!/^[A-Za-z]+$/.test(area) || !/^[A-Za-z0-9_]+$/.test(page)) {
            this.pageError(code);
            return;
        }

        var routeCode = area + "." + page;
        $("#pageTitle").html('<i class="fas fa-circle-notch fa-spin"></i>');

        var self = this;
        $.ajax({
            url: self.apiBase + "/page/check/" + encodeURIComponent(routeCode),
            type: "get",
            dataType: "text",
            success: function (exists) {
                if (self.normalizeResponse(exists) !== "true") {
                    self.pageError(routeCode);
                    return;
                }

                var loadUrl = self.apiBase + "/page/" + encodeURIComponent(routeCode);
                if (query) {
                    loadUrl += "?" + query;
                }

                $.ajax({
                    url: loadUrl,
                    type: "get",
                    dataType: "html",
                    success: function (html) {
                        self.errorCount = 0;
                        self.currentCode = routeCode;
                        $("#content").html(html);
                        window.scrollTo(0, 0);
                        document.cookie = "currentPage=" + routeCode + ";path=" + self.cookiePath() + ";SameSite=Lax";
                        if (typeof destroy === "function") {
                            destroy();
                        }
                        window.history.pushState(null, null, self.folderUrl + route);
                    },
                    error: function (xhr) {
                        self.handleRequestError(xhr, routeCode);
                    }
                });
            },
            error: function (xhr) {
                self.handleRequestError(xhr, routeCode);
            }
        });
    },

    handleRequestError: function (xhr, routeCode) {
        if (xhr && xhr.status === 401) {
            if (typeof login === "function") {
                login();
            }
            return;
        }

        if (xhr && xhr.responseJSON && xhr.responseJSON.error && typeof newError === "function") {
            newError(xhr.responseJSON.error);
        } else if (xhr && xhr.responseText) {
            try {
                var payload = JSON.parse(xhr.responseText);
                if (payload.error && typeof newError === "function") {
                    newError(payload.error);
                }
            } catch (ignore) {}
        }

        this.pageError(routeCode);
    },

    setPageTitle: function (title) {
        $("#pageTitle").html(title);
    },

    pageError: function (routeCode) {
        routeCode = routeCode || this.currentCode;

        if (this.errorCount >= 3) {
            $("#content").html(
                '<div class="card"><div class="card-body"><h3>Unable to load this page</h3>' +
                '<p>Refresh the browser or <a href="#" onclick="login();return false;">sign in again</a>.</p></div></div>'
            );
            return;
        }

        this.errorCount += 1;

        if (routeCode !== "Staff.Dashboard") {
            if (typeof newError === "function") {
                newError("That page can not be found");
            }
            this.loadPage("Staff.Dashboard");
            return;
        }

        if (typeof newError === "function") {
            newError("Unable to load the dashboard");
        }
    }
};

$("body").on("click", "a", function (e) {
    e.preventDefault();
    if ($(this).hasClass("web-page")) {
        urlRoute.loadPage($(this).attr("href"));
        return;
    }
    if ($(this).attr("data-toggle") === "modal") {
        $(".modal-backdrop").remove();
        return;
    }
    var href = $(this).attr("href");
    if (href && href !== "#" && href.charAt(0) !== "#") {
        window.open(href, "_blank");
    }
});

window.onpopstate = function () {
    urlRoute.loadPage(urlRoute.parseRouteFromUrl());
};
