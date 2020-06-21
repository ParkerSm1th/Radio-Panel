var urlRoute = {
    baseUrl: "",
    folderUrl: "",
    previousUrl: "",
    currentUrl: "",
    currentCode: "Staff.Dashboard",
    previousCode: "",
    folderUrl: function(e) {
        return this.folderUrl = e, this
    },
    setBaseUrl: function(e) {
        return this.baseUrl = e + "/", this
    },
    setCurrentCode: function(e) {
        return this.currentCode = e, this
    },
    setPreviousUrl: function(e) {
        return this.previousUrl = e, this
    },
    setPreviousCode: function(e) {
        return this.previousCode = e, this
    },
    getBaseUrl: function() {
        return this.baseUrl
    },
    checkCurrent: function(e) {
        if (this.baseUrl != document.URL) {
            document.URL;
            var t = document.URL.replace(this.baseUrl, "");
            this.loadPage(t);
        } else this.loadPage("Staff.Dashboard")
    },
    loadPage: function(e) {
        "/" != e.substring(0, 1) && (e = "/" + e), pathGlobal = e;
        var t = e.split("."),
            o = t[1].split("?");
        if (null == o[1]) r = "Staff";
        else var r = o[1];
        var l = this.baseUrl + "Pages" + t[0] + "/" + o[0] + ".php?" + r;
        var r = "Pages" + t[0] + "/" + o[0] + ".php";
        $("#pageTitle").html(`<i class="fas fa-circle-notch fa-spin"></i>`);
        urlRoute.loadPageContent(l, `${t[0]}.${o[0]}`, r), "function" == typeof destroy && destroy(), window.history.pushState(null, null, this.folderUrl + e)
    },
    setPageTitle: function(e) {
      $("#pageTitle").html(e);
    },
    setPageTitleCurrent: function(e) {
      $("#pageTitle").html(this.currentCode);
    },
    loadPageContent: function(e, o, r) {
        console.log(`[KeyFM PANEL] Testing URL ${r}`);
        $.ajax({
            url: 'scripts/testPage.php?page=' + r,
            type: "get",
            success: function(re) {
                if (re == 'true') {
                  urlRoute.currentUrl = e, urlRoute.getBaseUrl(), $.ajax({
                      url: e,
                      type: "get",
                      success: function(e) {
                          this.setPreviousCode = o;
                          console.log(`[KeyFM PANEL] Loading page ${o}`);
                          $("#content").html(e);
                          window.scrollTo(0, 0);
                          urlRoute.currentCode = o;
                          document.cookie="currentPage=" + o;
                      },
                      error: function() {
                          urlRoute.pageError();
                      }
                  });
                } else {
                  urlRoute.pageError();
                }
            },
            error: function() {
                urlRoute.pageError();
            }
        });
    },
    pageError: function() {
      newError('That page can not be found'), urlRoute.loadPage("Staff.Dashboard");
    }
};
$("body").on("click", "a", function(e) {
    if (e.preventDefault(), $(this).hasClass("web-page")) urlRoute.loadPage($(this).attr("href"));
    else if ("modal" == $(this).attr("data-toggle")) $(".modal-backdrop").remove();
    else {
        var t = $(this).attr("href");
        t && "#" !== t && (console.log("1"), "#" !== t.substring(0, 1) && (console.log("2"), "" !== t && window.open(t, "_blank")))
    }
}), window.onpopstate = function(e) {
    var t = document.URL.replace(urlRoute.getBaseUrl(), "");
    urlRoute.loadPage(t)
};
