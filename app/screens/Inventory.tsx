import { MaterialCommunityIcons } from "@expo/vector-icons";
import { StackNavigationProp } from "@react-navigation/stack";
import React, { useState } from "react";
import {
  SectionList,
  StyleSheet,
  View,
} from "react-native";
import {
  Appbar,
  Button,
  Card,
  IconButton,
  Searchbar,
  Text,
} from "react-native-paper";

// 🔹 Step 1: Define navigation stack types
type RootStackParamList = {
  InventoryMain: undefined;
  AddIngredient: undefined;
};

// 🔹 Step 2: Define prop types
type InventoryScreenNavigationProp = StackNavigationProp<
  RootStackParamList,
  'InventoryMain'
>;

type Props = {
  navigation: InventoryScreenNavigationProp;
};

// 🔹 Step 3: Ingredient type
type Ingredient = {
  ingredientName: string;
  quantity: number;
  category: string;
};

const categories = ["All", "Coffee", "Drinks", "Snacks", "Essentials"];

export default function InventoryMain({ navigation }: Props) {
  const [ingredients, setIngredients] = useState<Ingredient[]>([
    { ingredientName: "Sugar", quantity: 10, category: "Essentials" },
    { ingredientName: "Milk", quantity: 5, category: "Drinks" },
    { ingredientName: "Coffee Beans", quantity: 20, category: "Coffee" },
    { ingredientName: "Chips", quantity: 0, category: "Snacks" },
  ]);

  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);

  const filteredIngredients = ingredients.filter((item) => {
    const matchesSearch = item.ingredientName
      .toLowerCase()
      .includes(searchQuery.toLowerCase());
    const matchesCategory =
      !selectedCategory || selectedCategory === "All"
        ? true
        : item.category === selectedCategory;

    return matchesSearch && matchesCategory;
  });

  const groupedData = categories
    .filter((cat) => cat !== "All")
    .map((cat) => {
      const sortedItems = filteredIngredients
        .filter((item) => item.category === cat)
        .sort((a, b) => {
          if (a.quantity === 0 && b.quantity !== 0) return 1;
          if (a.quantity !== 0 && b.quantity === 0) return -1;
          return a.ingredientName.localeCompare(b.ingredientName);
        });

      return { title: cat, data: sortedItems };
    });

  const updateQuantity = (name: string, change: number) => {
    setIngredients((prev) =>
      prev.map((item) =>
        item.ingredientName === name
          ? { ...item, quantity: Math.max(0, item.quantity + change) }
          : item
      )
    );
  };

  return (
    <View style={styles.container}>
      <Appbar.Header style={{ backgroundColor: "#6D4C41" }}>
        <Appbar.Content title="Inventory" color="#fff" />
      </Appbar.Header>

      <Searchbar
        placeholder="Search ingredients..."
        value={searchQuery}
        onChangeText={(query) => setSearchQuery(query)}
        style={styles.searchBar}
      />

      <View style={styles.buttonContainer}>
        <Button
          mode="contained"
          onPress={() => navigation.navigate("AddIngredient")}
          style={styles.addButton}
        >
          Add Ingredient
        </Button>
      </View>

      <View style={styles.categoryContainer}>
        {categories.map((cat) => (
          <Button
            key={cat}
            mode={selectedCategory === cat ? "contained" : "outlined"}
            onPress={() =>
              setSelectedCategory(selectedCategory === cat ? null : cat)
            }
            style={styles.categoryButton}
          >
            {cat}
          </Button>
        ))}
      </View>

      <SectionList
        sections={groupedData}
        keyExtractor={(item, index) => item.ingredientName + index}
        renderItem={({ item }) => (
          <Card
            style={[
              styles.card,
              item.quantity === 0 ? styles.outOfStockCard : null,
            ]}
          >
            <Card.Content style={styles.cardContent}>
              <View
                style={{ flex: 1, flexDirection: "row", alignItems: "center" }}
              >
                <Text
                  style={[
                    styles.name,
                    item.quantity <= 10 ? styles.lowStockName : null,
                  ]}
                >
                  {item.ingredientName}
                </Text>
                {item.quantity > 0 && item.quantity <= 10 && (
                  <MaterialCommunityIcons
                    name="alert-circle"
                    size={18}
                    color="red"
                    style={{ marginLeft: 6 }}
                  />
                )}
              </View>

              <View style={{ flex: 1 }}>
                <Text
                  style={[
                    item.quantity === 0
                      ? styles.outOfStock
                      : item.quantity <= 10
                      ? styles.lowStockQty
                      : styles.inStock,
                  ]}
                >
                  {item.quantity === 0
                    ? "Out of Stock"
                    : `Quantity: ${item.quantity}`}
                </Text>
              </View>

              <View style={styles.actions}>
                <IconButton
                  icon="minus"
                  size={20}
                  onPress={() => updateQuantity(item.ingredientName, -1)}
                  disabled={item.quantity === 0}
                  iconColor={item.quantity === 0 ? "gray" : "black"}
                />
                <IconButton
                  icon="plus"
                  size={20}
                  onPress={() => updateQuantity(item.ingredientName, 1)}
                  iconColor="black"
                />
              </View>
            </Card.Content>
          </Card>
        )}
        renderSectionHeader={({ section: { title } }) =>
          groupedData.find((s) => s.title === title)?.data.length > 0 ? (
            <View style={styles.sectionHeader}>
              <Text style={styles.sectionTitle}>{title}</Text>
            </View>
          ) : null
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Text>No ingredients found.</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fff",
  },
  searchBar: {
    margin: 12,
    borderRadius: 8,
  },
  buttonContainer: {
    paddingHorizontal: 12,
    marginBottom: 8,
  },
  addButton: {
    borderRadius: 8,
    backgroundColor: "#6D4C41",
    paddingVertical: 6,
  },
  categoryContainer: {
    flexDirection: "row",
    flexWrap: "wrap",
    justifyContent: "center",
    marginBottom: 10,
  },
  categoryButton: {
    margin: 4,
    borderRadius: 20,
  },
  emptyContainer: {
    flex: 1,
    alignItems: "center",
    justifyContent: "center",
    marginTop: 20,
  },
  sectionHeader: {
    backgroundColor: "#EFEFEF",
    paddingVertical: 6,
    paddingHorizontal: 12,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#6D4C41",
  },
  card: {
    marginHorizontal: 10,
    marginVertical: 4,
    borderRadius: 8,
    elevation: 2,
    backgroundColor: "#fff",
  },
  outOfStockCard: {
    backgroundColor: "#f2f2f2",
    opacity: 0.7,
  },
  cardContent: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
  },
  name: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#000",
  },
  lowStockName: {
    color: "red",
  },
  inStock: {
    fontSize: 14,
    color: "#333",
  },
  lowStockQty: {
    fontSize: 14,
    fontWeight: "bold",
    color: "red",
  },
  outOfStock: {
    fontSize: 14,
    fontWeight: "bold",
    color: "gray",
  },
  actions: {
    flexDirection: "row",
    alignItems: "center",
  },
});
