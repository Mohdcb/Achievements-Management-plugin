;(($) => {
  $(document).ready(() => {
    // Variables
    let achievements = []
    let categories = []

    // Load achievements
    function loadAchievements() {
      achievements = []
      $(".ach-item").each(function () {
        const id = $(this).data("id")
        const name = $(this).find(".ach-item-name").text()
        const category = $(this).find(".ach-item-category").text()
        const icon = $(this).find(".ach-item-icon i").attr("class").replace("dashicons ", "")
        const dateText = $(this).find(".ach-item-date").text()
        const date = dateText ? formatDateForStorage(dateText) : formatDateForStorage(new Date())

        achievements.push({
          id: id,
          name: name,
          category: category,
          icon: icon,
          date: date,
        })
      })
    }

    // Load categories
    function loadCategories() {
      categories = []
      $(".ach-category-option").each(function () {
        const category = $(this).data("category")
        if (category && category !== "new") {
          categories.push(category)
        }
      })
    }

    // Format date for display (from storage format)
    function formatDateForDisplay(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" })
    }

    // Format date for storage (YYYY-MM-DD)
    function formatDateForStorage(dateString) {
      const date = new Date(dateString)
      return date.toISOString().split("T")[0]
    }

    // Initialize
    loadAchievements()
    loadCategories()

    // Initialize datepicker
    $(".ach-datepicker").datepicker({
      dateFormat: "yy-mm-dd",
      changeMonth: true,
      changeYear: true,
    })

    // Variables
    let isEditing = false
    let currentId = ""

    // Toggle icon grid
    $(".ach-toggle-icons").on("click", () => {
      $(".ach-icons-grid").toggle()
    })

    // Select icon
    $(".ach-icon-option").on("click", function () {
      const icon = $(this).data("icon")
      $("#ach-icon").val(icon)
      $(".ach-selected-icon i").attr("class", "dashicons " + icon)
      $(".ach-icon-option").removeClass("selected")
      $(this).addClass("selected")
      $(".ach-icons-grid").hide()
    })

    // Category input focus
    $("#ach-category").on("focus", () => {
      $(".ach-category-suggestions").show()
    })

    // Category input blur
    $(document).on("click", (e) => {
      if (!$(e.target).closest(".ach-category-selector").length) {
        $(".ach-category-suggestions").hide()
      }
    })

    // Select category
    $(".ach-category-option").on("click", function () {
      const category = $(this).data("category")

      if (category === "new") {
        const newCategory = prompt("Enter new category name:")
        if (newCategory && newCategory.trim() !== "") {
          $("#ach-category").val(newCategory.trim())

          // Add to categories list if not exists
          if (categories.indexOf(newCategory.trim()) === -1) {
            categories.push(newCategory.trim())

            // Add to UI
            const newOption = $(
              '<div class="ach-category-option" data-category="' +
                newCategory.trim() +
                '">' +
                newCategory.trim() +
                "</div>",
            )
            $(".ach-add-category").before(newOption)

            // Add to shortcode generator
            $("#ach-shortcode-category").append(
              '<option value="' + newCategory.trim() + '">' + newCategory.trim() + "</option>",
            )

            // Bind click event
            newOption.on("click", function () {
              $("#ach-category").val($(this).data("category"))
              $(".ach-category-suggestions").hide()
            })
          }
        }
      } else {
        $("#ach-category").val(category)
        $(".ach-category-suggestions").hide()
      }
    })

    // Add new achievement
    $(".ach-add-new").on("click", () => {
      resetForm()
      $(".ach-form-title").text("Add New Achievement")
      $(".ach-form-container").show()

      // Set default date to today
      $("#ach-date").val(formatDateForStorage(new Date()))
    })

    // Cancel form
    $(".ach-cancel-form").on("click", () => {
      $(".ach-form-container").hide()
    })

    // Edit achievement
    $(document).on("click", ".ach-edit-item", function () {
      const item = $(this).closest(".ach-item")
      const id = item.data("id")
      const name = item.find(".ach-item-name").text()
      const category = item.find(".ach-item-category").text()
      const icon = item.find(".ach-item-icon i").attr("class").replace("dashicons ", "")
      const dateText = item.find(".ach-item-date").text()
      const date = dateText ? formatDateForStorage(dateText) : formatDateForStorage(new Date())

      $("#ach-item-id").val(id)
      $("#ach-name").val(name)
      $("#ach-category").val(category)
      $("#ach-icon").val(icon)
      $("#ach-date").val(date)
      $(".ach-selected-icon i").attr("class", "dashicons " + icon)

      $(".ach-form-title").text("Edit Achievement")
      $(".ach-form-container").show()
      isEditing = true
      currentId = id
    })

    // Delete achievement
    $(document).on("click", ".ach-delete-item", function () {
      if (confirm("Are you sure you want to delete this achievement?")) {
        $(this).closest(".ach-item").remove()
        loadAchievements()
      }
    })

    // Save achievement
    $("#ach-achievement-form").on("submit", (e) => {
      e.preventDefault()

      const id = $("#ach-item-id").val() || "ach_" + Date.now()
      const name = $("#ach-name").val()
      const category = $("#ach-category").val()
      const icon = $("#ach-icon").val()
      const date = $("#ach-date").val()

      if (!name || !category || !icon || !date) {
        alert("Please fill in all fields")
        return
      }

      if (isEditing) {
        // Update existing item
        const item = $('.ach-item[data-id="' + currentId + '"]')
        item.find(".ach-item-name").text(name)
        item.find(".ach-item-category").text(category)
        item.find(".ach-item-icon i").attr("class", "dashicons " + icon)
        item.find(".ach-item-date").text(formatDateForDisplay(date))
      } else {
        // Add new item
        const newItem = `
          <div class="ach-item" data-id="${id}">
            <div class="ach-item-header">
              <span class="ach-item-icon"><i class="dashicons ${icon}"></i></span>
              <div class="ach-item-details">
                <span class="ach-item-name">${name}</span>
                <span class="ach-item-meta">
                  <span class="ach-item-category">${category}</span>
                  <span class="ach-item-date">${formatDateForDisplay(date)}</span>
                </span>
              </div>
              <div class="ach-item-actions">
                <button type="button" class="button ach-edit-item">Edit</button>
                <button type="button" class="button ach-delete-item">Delete</button>
              </div>
            </div>
          </div>
        `

        $(".ach-empty-state").remove()
        if (!$(".ach-items").length) {
          $(".ach-items-container").append('<div class="ach-items"></div>')
        }
        $(".ach-items").append(newItem)
      }

      // Reset and hide form
      resetForm()
      $(".ach-form-container").hide()
      loadAchievements()

      // Add category to list if it's new
      if (categories.indexOf(category) === -1) {
        categories.push(category)

        // Add to UI
        const newOption = $('<div class="ach-category-option" data-category="' + category + '">' + category + "</div>")
        $(".ach-add-category").before(newOption)

        // Add to shortcode generator
        $("#ach-shortcode-category").append('<option value="' + category + '">' + category + "</option>")

        // Bind click event
        newOption.on("click", function () {
          $("#ach-category").val($(this).data("category"))
          $(".ach-category-suggestions").hide()
        })
      }
    })

    // Generate shortcode
    function updateShortcode() {
      let shortcode = "[display_achievements"

      const category = $("#ach-shortcode-category").val()
      const limit = $("#ach-shortcode-limit").val()

      if (category) {
        shortcode += ' category="' + category + '"'
      }

      if (limit && limit !== "-1") {
        shortcode += ' limit="' + limit + '"'
      }

      shortcode += "]"

      $("#ach-shortcode-result").val(shortcode)
    }

    // Update shortcode on change
    $("#ach-shortcode-category, #ach-shortcode-limit").on("change", updateShortcode)

    // Copy shortcode
    $(".ach-copy-shortcode").on("click", function () {
      $("#ach-shortcode-result").select()
      document.execCommand("copy")
      $(this).text("Copied!")
      setTimeout(() => {
        $(this).text("Copy")
      }, 2000)
    })

    // Save all changes
    $(".ach-save-all").on("click", function () {
      const saveBtn = $(this)
      const statusEl = $(".ach-save-status")

      saveBtn.prop("disabled", true)
      statusEl.text("Saving...")

      // Check if achObj is defined
      if (typeof achObj === "undefined") {
        console.error("achObj is not defined. Make sure it is properly enqueued.")
        statusEl.text("Error: achObj is not defined.")
        saveBtn.prop("disabled", false)
        return
      }

      // Save achievements
      $.ajax({
        url: achObj.ajaxurl,
        type: "POST",
        data: {
          action: "ach_save_achievements",
          nonce: achObj.nonce,
          achievements: JSON.stringify(achievements),
        },
        success: (response) => {
          if (response.success) {
            // Save categories
            $.ajax({
              url: achObj.ajaxurl,
              type: "POST",
              data: {
                action: "ach_save_categories",
                nonce: achObj.nonce,
                categories: JSON.stringify(categories),
              },
              success: (catResponse) => {
                if (catResponse.success) {
                  statusEl.text("Saved successfully!")
                } else {
                  statusEl.text("Error saving categories: " + catResponse.data)
                }
              },
              error: () => {
                statusEl.text("Error saving categories")
              },
              complete: () => {
                saveBtn.prop("disabled", false)
                setTimeout(() => {
                  statusEl.text("")
                }, 3000)
              },
            })
          } else {
            statusEl.text("Error: " + response.data)
            saveBtn.prop("disabled", false)
          }
        },
        error: () => {
          statusEl.text("Error saving achievements")
          saveBtn.prop("disabled", false)
        },
      })
    })

    // Helper function to reset form
    function resetForm() {
      $("#ach-item-id").val("")
      $("#ach-name").val("")
      $("#ach-category").val("")
      $("#ach-icon").val("dashicons-awards")
      $("#ach-date").val("")
      $(".ach-selected-icon i").attr("class", "dashicons dashicons-awards")
      isEditing = false
      currentId = ""
    }

    // Initialize shortcode
    updateShortcode()
  })
})(jQuery)
