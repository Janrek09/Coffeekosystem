// ReportsTab.tsx
import React, { useMemo, useState } from "react";
import { Dimensions, ScrollView, StyleSheet, View } from "react-native";
import { LineChart, PieChart } from "react-native-chart-kit";
import {
  Appbar,
  Button,
  Card,
  Divider,
  ProgressBar,
  Text,
} from "react-native-paper";

export default function ReportsTab() {
  const [viewType, setViewType] = useState<"daily" | "weekly" | "monthly">(
    "daily"
  );

  // Dummy simulated sales data
  const salesData = [
    { date: "2025-08-15", product: "Latte", quantity: 5, price: 150 },
    { date: "2025-08-15", product: "Espresso", quantity: 3, price: 120 },
    { date: "2025-08-16", product: "Cappuccino", quantity: 4, price: 140 },
    { date: "2025-08-17", product: "Latte", quantity: 7, price: 150 },
    { date: "2025-08-17", product: "Mocha", quantity: 2, price: 160 },
    { date: "2025-08-18", product: "Espresso", quantity: 6, price: 120 },
    { date: "2025-08-18", product: "Latte", quantity: 0, price: 150 },
  ];

  // --- Helpers ---
  const aggregateData = () => {
    const grouped: Record<string, number> = {};
    for (const sale of salesData) {
      const revenue = sale.quantity * sale.price;
      let key = "";
      if (viewType === "daily") key = sale.date;
      else if (viewType === "weekly") key = "Week of " + sale.date.slice(0, 7);
      else if (viewType === "monthly") key = sale.date.slice(0, 7);
      grouped[key] = (grouped[key] || 0) + revenue;
    }
    return grouped;
  };

  const breakdownForPeriod = (periodKey: string) => {
    const grouped: Record<string, { qty: number; revenue: number }> = {};
    salesData.forEach((sale) => {
      let key = "";
      if (viewType === "weekly") key = "Week of " + sale.date.slice(0, 7);
      else if (viewType === "monthly") key = sale.date.slice(0, 7);
      else key = sale.date;

      if (key === periodKey) {
        if (!grouped[sale.product]) {
          grouped[sale.product] = { qty: 0, revenue: 0 };
        }
        grouped[sale.product].qty += sale.quantity;
        grouped[sale.product].revenue += sale.quantity * sale.price;
      }
    });
    return grouped;
  };

  // Derived values
  const grouped = aggregateData();
  const labels = Object.keys(grouped);
  const dataPoints = Object.values(grouped);

  const totalRevenue = useMemo(
    () => dataPoints.reduce((a, b) => a + b, 0),
    [dataPoints]
  );
  const totalTransactions = salesData.length;

  // Best Sellers (global)
  const productTotals: Record<string, number> = {};
  salesData.forEach((s) => {
    productTotals[s.product] =
      (productTotals[s.product] || 0) + s.quantity * s.price;
  });
  const bestSellersData = Object.entries(productTotals).map(([prod, rev], i) => {
    const colors = ["#f59e0b", "#3b82f6", "#10b981", "#ef4444", "#8b5cf6"];
    return {
      name: prod,
      revenue: rev,
      color: colors[i % colors.length],
      legendFontColor: "#333",
      legendFontSize: 12,
    };
  });

  return (
    <View style={styles.container}>
      <Appbar.Header style={{ backgroundColor: "#6D4C41" }}>
        <Appbar.Content title="Reports" color="#fff" />
      </Appbar.Header>

      {/* Toggle buttons */}
      <View style={styles.toggleContainer}>
        <Button
          mode={viewType === "daily" ? "contained" : "outlined"}
          onPress={() => setViewType("daily")}
          style={styles.toggleBtn}
        >
          Daily
        </Button>
        <Button
          mode={viewType === "weekly" ? "contained" : "outlined"}
          onPress={() => setViewType("weekly")}
          style={styles.toggleBtn}
        >
          Weekly
        </Button>
        <Button
          mode={viewType === "monthly" ? "contained" : "outlined"}
          onPress={() => setViewType("monthly")}
          style={styles.toggleBtn}
        >
          Monthly
        </Button>
      </View>

      <ScrollView>
        {/* Summary Cards */}
        <View style={styles.summaryContainer}>
          <Card style={styles.card}>
            <Card.Content>
              <Text style={styles.cardTitle}>Total Sales</Text>
              <Text style={styles.cardValue}>₱{totalRevenue}</Text>
            </Card.Content>
          </Card>
          <Card style={styles.card}>
            <Card.Content>
              <Text style={styles.cardTitle}>Transactions</Text>
              <Text style={styles.cardValue}>{totalTransactions}</Text>
            </Card.Content>
          </Card>
        </View>

        {/* MAIN CONTENT */}
        <View style={{ alignItems: "center", marginVertical: 10 }}>
          {viewType === "daily" ? (
            // DAILY view (with hourly trend)
            labels.map((date, idx) => {
              const breakdown = breakdownForPeriod(date);
              const totalForDate = Object.values(breakdown).reduce(
                (sum, d) => sum + d.revenue,
                0
              );

              const ranked = Object.entries(breakdown).sort(
                (a, b) => b[1].revenue - a[1].revenue
              );

              return (
                <View
                  key={idx}
                  style={{ width: Dimensions.get("window").width - 20 }}
                >
                  <Text style={styles.sectionHeader}>
                    {date} – Revenue Trend
                  </Text>

                  {/* Product Breakdown */}
                  <Card style={{ marginHorizontal: 5, borderRadius: 12 }}>
                    <Card.Content>
                      <Text style={styles.breakdownTitle}>
                        Products (High → Low)
                      </Text>
                      <Divider style={{ marginVertical: 6 }} />
                      {ranked.map(([prod, details], i) => {
                        const share =
                          totalForDate > 0 ? details.revenue / totalForDate : 0;
                        return (
                          <View key={i} style={{ marginBottom: 10 }}>
                            <View style={styles.row}>
                              <Text style={styles.prodName}>
                                {i + 1}. {prod}
                              </Text>
                              <Text style={styles.prodRight}>
                                {details.qty} pcs · ₱{details.revenue}
                              </Text>
                            </View>
                            <ProgressBar
                              progress={share}
                              style={styles.progress}
                            />
                          </View>
                        );
                      })}
                      <Divider style={{ marginTop: 8 }} />
                      <Text style={{ marginTop: 8, fontWeight: "bold" }}>
                        Total: ₱{totalForDate}
                      </Text>
                    </Card.Content>
                  </Card>
                  <View style={{ height: 18 }} />
                </View>
              );
            })
          ) : (
            // WEEKLY / MONTHLY summary with trend + breakdown
            <View style={{ width: Dimensions.get("window").width - 20 }}>
              <Text style={styles.sectionHeader}>
                {viewType === "weekly" ? "Weekly" : "Monthly"} Revenue Trend
              </Text>
              <LineChart
                data={{
                  labels,
                  datasets: [{ data: dataPoints }],
                }}
                width={Dimensions.get("window").width - 30}
                height={220}
                yAxisLabel="₱"
                chartConfig={chartConfig}
                bezier
                style={{ borderRadius: 12, marginBottom: 12 }}
              />

              {labels.map((period, idx) => {
                const breakdown = breakdownForPeriod(period);
                const totalForPeriod = Object.values(breakdown).reduce(
                  (sum, d) => sum + d.revenue,
                  0
                );
                const ranked = Object.entries(breakdown).sort(
                  (a, b) => b[1].revenue - a[1].revenue
                );

                return (
                  <Card
                    key={idx}
                    style={{ marginVertical: 6, borderRadius: 12 }}
                  >
                    <Card.Content>
                      <Text style={styles.breakdownTitle}>
                        {period} – Product Breakdown
                      </Text>
                      <Divider style={{ marginVertical: 6 }} />
                      {ranked.map(([prod, details], i) => {
                        const share =
                          totalForPeriod > 0
                            ? details.revenue / totalForPeriod
                            : 0;
                        return (
                          <View key={i} style={{ marginBottom: 10 }}>
                            <View style={styles.row}>
                              <Text style={styles.prodName}>
                                {i + 1}. {prod}
                              </Text>
                              <Text style={styles.prodRight}>
                                {details.qty} pcs · ₱{details.revenue}
                              </Text>
                            </View>
                            <ProgressBar
                              progress={share}
                              style={styles.progress}
                            />
                          </View>
                        );
                      })}
                      <Divider style={{ marginTop: 8 }} />
                      <Text style={{ marginTop: 8, fontWeight: "bold" }}>
                        Total: ₱{totalForPeriod}
                      </Text>
                    </Card.Content>
                  </Card>
                );
              })}
            </View>
          )}
        </View>

        {/* Best Sellers Section */}
        <Card style={{ margin: 10, borderRadius: 12 }}>
          <Card.Content>
            <Text style={styles.sectionHeader}>Best-Selling Coffee</Text>
            <PieChart
              data={bestSellersData.map((d) => ({
                name: d.name,
                population: d.revenue,
                color: d.color,
                legendFontColor: d.legendFontColor,
                legendFontSize: d.legendFontSize,
              }))}
              width={Dimensions.get("window").width - 40}
              height={220}
              chartConfig={chartConfig}
              accessor={"population"}
              backgroundColor={"transparent"}
              paddingLeft={"15"}
              absolute
            />
          </Card.Content>
        </Card>
      </ScrollView>
    </View>
  );
}

const chartConfig = {
  backgroundColor: "#6D4C41",
  backgroundGradientFrom: "#8D6E63",
  backgroundGradientTo: "#6D4C41",
  decimalPlaces: 0,
  color: (opacity = 1) => `rgba(255,255,255,${opacity})`,
  labelColor: (opacity = 1) => `rgba(255,255,255,${opacity})`,
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#fff" },
  toggleContainer: {
    flexDirection: "row",
    justifyContent: "space-around",
    marginVertical: 10,
    paddingHorizontal: 10,
  },
  toggleBtn: { flex: 1, marginHorizontal: 4 },
  summaryContainer: {
    flexDirection: "row",
    justifyContent: "space-around",
    marginVertical: 10,
  },
  card: { flex: 1, marginHorizontal: 6, borderRadius: 10, elevation: 3 },
  cardTitle: { fontSize: 14, color: "#6D4C41" },
  cardValue: { fontSize: 18, fontWeight: "bold", marginTop: 4 },
  sectionHeader: { fontWeight: "bold", fontSize: 16, marginBottom: 8 },
  breakdownTitle: { fontSize: 16, fontWeight: "bold" },
  row: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "baseline",
  },
  prodName: { fontSize: 14, fontWeight: "600" },
  prodRight: { fontSize: 13 },
  progress: { height: 6, borderRadius: 6, marginTop: 6 },
});
