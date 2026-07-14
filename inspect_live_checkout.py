import requests
from bs4 import BeautifulSoup

session = requests.Session()
# Set headers to look like a browser
session.headers.update({
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
})

print("Fetching home page to get cookies...")
r = session.get("https://celzimoveste.cl/")
print("Status:", r.status_code)

# Let's find a product ID to add to cart.
# We can fetch a product page or use a search to find one.
# Let's search for a product on the site.
print("Searching for products...")
r_search = session.get("https://celzimoveste.cl/?s=vestido")
soup = BeautifulSoup(r_search.text, 'html.parser')

product_id = None
# Look for add to cart button or product link
for a in soup.find_all('a', href=True):
    if '/producto/' in a['href']:
        # Fetch the product page to find the add to cart form
        print("Found product link:", a['href'])
        r_prod = session.get(a['href'])
        soup_prod = BeautifulSoup(r_prod.text, 'html.parser')
        # Find single product add to cart button value
        btn = soup_prod.find('button', {'name': 'add-to-cart'})
        if btn:
            product_id = btn.get('value')
            print("Found product ID from button value:", product_id)
            break
        # Or look for variations form
        var_form = soup_prod.find('form', class_='variations_form')
        if var_form:
            product_id = var_form.get('data-product_id')
            print("Found product ID from variations form:", product_id)
            # Let's check variation id if variable
            var_data = var_form.get('data-product_variations')
            if var_data:
                import json
                try:
                    variations = json.loads(var_data)
                    if variations:
                        # Use the first active variation ID
                        product_id = variations[0]['variation_id']
                        print("Using variation ID:", product_id)
                except Exception as e:
                    print("Error parsing variations:", e)
            break

if not product_id:
    # Let's try adding a default product ID (e.g. 100 or check products.csv in the theme directory)
    print("Could not find product dynamically. Reading products.csv...")
    try:
        with open('celzimo-theme/products.csv', 'r', encoding='utf-8') as f:
            lines = f.readlines()
            # Let's parse the first product ID
            if len(lines) > 1:
                parts = lines[1].split(',')
                # Try to guess product ID, or search the site for products.
                print("CSV line:", lines[1])
    except Exception as e:
        print("Error reading CSV:", e)
    # Default fallback
    product_id = "1391" # Let's try 1391 or search for any number

# Add to cart via WooCommerce AJAX or POST
print(f"Adding product {product_id} to cart...")
r_add = session.post("https://celzimoveste.cl/?wc-ajax=add_to_cart", data={
    'product_id': product_id,
    'quantity': 1
})
print("Add to cart response:", r_add.status_code, r_add.text)

# Fetch checkout page
print("Fetching checkout page...")
r_checkout = session.get("https://celzimoveste.cl/finalizar-compra/")
print("Checkout Page Status:", r_checkout.status_code)

# Save HTML for inspection
with open("checkout_dom.html", "w", encoding="utf-8") as f:
    f.write(r_checkout.text)
print("Saved HTML to checkout_dom.html.")

# Let's inspect the HTML using BeautifulSoup
soup_checkout = BeautifulSoup(r_checkout.text, 'html.parser')

print("\n--- CHECKING CHECKOUT DOM STRUCTURE ---")
# Check if ship-to-different-address-checkbox exists
native_checkbox = soup_checkout.find(id="ship-to-different-address-checkbox")
if native_checkbox:
    print("SUCCESS: Found #ship-to-different-address-checkbox")
    print(native_checkbox)
else:
    print("WARNING: #ship-to-different-address-checkbox NOT found by ID!")
    # Look for any input with name ship_to_different_address
    cb = soup_checkout.find('input', {'name': 'ship_to_different_address'})
    if cb:
        print("Found ship_to_different_address checkbox:", cb)
    else:
        print("No ship_to_different_address checkbox found at all!")

# Look for shipping address container
shipping_address = soup_checkout.find(class_="shipping_address")
if shipping_address:
    print("SUCCESS: Found class .shipping_address wrapper")
    # Print its classes and style
    print("Classes:", shipping_address.get('class'), "Style:", shipping_address.get('style'))
else:
    # Look for any other shipping fields container
    print("WARNING: .shipping_address wrapper NOT found by class!")
    # Look for shipping_address_1 input
    ship_addr1 = soup_checkout.find(id="shipping_address_1")
    if ship_addr1:
        print("Found #shipping_address_1 input, so shipping fields exist in the DOM.")
    else:
        print("No #shipping_address_1 input found in the DOM!")

# Print custom billing fields
print("\n--- CUSTOM BILLING FIELDS ---")
for field_id in ['billing_solicita_factura', 'billing_factura_diff_address', 'billing_rut', 'billing_razon_social', 'billing_giro']:
    el = soup_checkout.find(id=field_id)
    if el:
        print(f"Found {field_id}:", el.name, el.get('type'), el.get('class'))
    else:
        print(f"NOT found: {field_id}")
